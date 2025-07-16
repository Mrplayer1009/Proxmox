<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PdfController;
use App\Http\Controllers\ClientAbonnementController;
use App\Http\Controllers\AnnonceController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Espace client
Route::middleware(['auth'])->group(function () {
    Route::get('/client/dashboard', [App\Http\Controllers\ClientController::class, 'dashboard'])->name('client.dashboard');
    Route::get('/client/services', [App\Http\Controllers\ClientController::class, 'services'])->name('client.services');
    Route::get('/client/annonces', [App\Http\Controllers\ClientController::class, 'annonces'])->name('client.annonces');
    Route::get('/client/annonces/create', [App\Http\Controllers\ClientController::class, 'createAnnonce'])->name('client.annonces.create');
    Route::post('/client/annonces', [App\Http\Controllers\ClientController::class, 'storeAnnonce'])->name('client.annonces.store');
    Route::post('/client/annonces/{id}/acheter', [App\Http\Controllers\ClientController::class, 'acheterAnnonce'])->name('client.annonces.acheter');
    Route::get('/client/paiements', [App\Http\Controllers\ClientController::class, 'paiements'])->name('client.paiements');
    Route::delete('/client/annonces/{id}', [App\Http\Controllers\ClientController::class, 'destroyAnnonce'])->name('client.annonces.destroy');
    Route::post('/client/annonces/{id}/changer-stock', [App\Http\Controllers\ClientController::class, 'changerStock'])->name('client.annonces.changer_stock');

    // Espace livreur
    Route::get('/livreur/dashboard', [App\Http\Controllers\LivreurController::class, 'dashboard'])->name('livreur.dashboard');
    Route::post('/livreur/upload-piece', [App\Http\Controllers\LivreurController::class, 'uploadPiece'])->name('livreur.upload_piece');
    Route::get('/livreur/services', [App\Http\Controllers\LivreurController::class, 'services'])->name('livreur.services');
    Route::get('/livreur/paiements', [App\Http\Controllers\LivreurController::class, 'paiementsLivreur'])->name('livreur.paiements');
    Route::get('/livreur/paiements/pdf', [App\Http\Controllers\LivreurController::class, 'paiementsPdf'])->name('livreur.paiements.pdf');
    Route::get('/livreur/paiements/{id}/pdf', [App\Http\Controllers\LivreurController::class, 'paiementPdf'])->name('livreur.paiement.pdf');

    // Delivery locations management
    Route::get('/livreur/deliveries', [App\Http\Controllers\LivreurController::class, 'deliveries'])->name('livreur.deliveries');
    Route::get('/livreur/deliveries/{id}', [App\Http\Controllers\LivreurController::class, 'showDelivery'])->name('livreur.deliveries.show');
    Route::post('/livreur/deliveries/{id}/locations', [App\Http\Controllers\LivreurController::class, 'updateLocations'])->name('livreur.deliveries.locations.update');
    Route::post('/livreur/deliveries/{id}/livree', [App\Http\Controllers\LivreurController::class, 'marquerLivree'])->name('livreur.deliveries.livree');
    Route::post('/livreur/deliveries/{id}/add-location', [App\Http\Controllers\LivreurController::class, 'addLocation'])->name('livreur.deliveries.add_location');

    // Planning management
    Route::get('/livreur/planning', [App\Http\Controllers\LivreurController::class, 'planning'])->name('livreur.planning.index');
    Route::get('/livreur/planning/create', [App\Http\Controllers\LivreurController::class, 'createPlanning'])->name('livreur.planning.create');
    Route::post('/livreur/planning', [App\Http\Controllers\LivreurController::class, 'storePlanning'])->name('livreur.planning.store');

    // Annonce creation
    Route::get('/livreur/annonces/create', [App\Http\Controllers\LivreurController::class, 'createAnnonce'])->name('livreur.annonces.create');
    Route::post('/livreur/annonces', [App\Http\Controllers\LivreurController::class, 'storeAnnonce'])->name('livreur.annonces.store');

    // Espace commerçant
    Route::get('/commercant/dashboard', [App\Http\Controllers\CommercantController::class, 'dashboard'])->name('commercant.dashboard');

    // Product CRUD routes for commercant
    Route::post('/commercant/produits', [App\Http\Controllers\CommercantController::class, 'storeProduct'])->name('commercant.produits.store');
    Route::put('/commercant/produits/{id}', [App\Http\Controllers\CommercantController::class, 'updateProduct'])->name('commercant.produits.update');
    Route::delete('/commercant/produits/{id}', [App\Http\Controllers\CommercantController::class, 'deleteProduct'])->name('commercant.produits.delete');
    Route::post('/commercant/produits/{id}/toggle-affiche', [App\Http\Controllers\CommercantController::class, 'toggleAffiche'])->name('commercant.produits.toggleAffiche');
    Route::post('/commercant/entreprise', [App\Http\Controllers\CommercantController::class, 'storeEntreprise'])->name('commercant.entreprise.store');
    Route::post('/commercant/{id}/contrat', [App\Http\Controllers\CommercantController::class, 'storeContrat'])->name('commercant.contrat.store');

    // Espace prestataire
    Route::get('/prestataire/dashboard', [App\Http\Controllers\PrestataireController::class, 'dashboard'])->name('prestataire.dashboard');
    Route::get('/prestataire/inscription', [App\Http\Controllers\PrestataireController::class, 'showInscription'])->name('prestataire.inscription.show');
    Route::post('/prestataire/inscription', [App\Http\Controllers\PrestataireController::class, 'storeInscription'])->name('prestataire.inscription.store');
    Route::get('/prestataire/calendrier', [App\Http\Controllers\PrestataireController::class, 'calendrier'])->name('prestataire.calendrier');
    Route::get('/prestataire/interventions', [App\Http\Controllers\PrestataireController::class, 'interventions'])->name('prestataire.interventions');
    Route::get('/prestataire/factures', [App\Http\Controllers\PrestataireController::class, 'factures'])->name('prestataire.factures');
    Route::get('/prestataire/factures/{id}/pdf', [App\Http\Controllers\PrestataireController::class, 'facturePdf'])->name('prestataire.factures.pdf');
    Route::patch('/prestataire/interventions/{id}/annuler', [App\Http\Controllers\PrestataireController::class, 'annulerIntervention'])->name('prestataire.annuler_intervention');
    Route::post('/prestataire/upload-piece', [App\Http\Controllers\PrestataireController::class, 'uploadPiece'])->name('prestataire.upload_piece');

    // Espace admin
    Route::get('/admin/dashboard', [App\Http\Controllers\AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('/admin/stripe', [App\Http\Controllers\StripeController::class, 'index'])->name('admin.stripe');
    Route::get('/admin/prestataires', [App\Http\Controllers\AdminController::class, 'prestataires'])->name('admin.prestataires');
    Route::post('/admin/prestataires/{id}/valider', [App\Http\Controllers\AdminController::class, 'validerPrestataire'])->name('admin.prestataires.valider');
    Route::post('/admin/prestataires/{id}/refuser', [App\Http\Controllers\AdminController::class, 'refuserPrestataire'])->name('admin.prestataires.refuser');
    Route::get('/admin/livreurs-validation', [App\Http\Controllers\AdminController::class, 'validerLivreurs'])->name('admin.livreurs.validation');
    Route::post('/admin/livreurs/{id}/changer-statut', [App\Http\Controllers\AdminController::class, 'changerStatutLivreur'])->name('admin.livreurs.changer_statut');
    Route::get('/admin/batiment/creer', [App\Http\Controllers\AdminController::class, 'createBatiment'])->name('admin.batiment.create');
    Route::post('/admin/batiment/creer', [App\Http\Controllers\AdminController::class, 'storeBatiment'])->name('admin.batiment.store');
    Route::get('/admin/batiments', [App\Http\Controllers\AdminController::class, 'batiments'])->name('admin.batiments');
    Route::get('/admin/batiment/{id}/edit', [App\Http\Controllers\AdminController::class, 'editBatiment'])->name('admin.batiment.edit');
    Route::post('/admin/batiment/{id}/edit', [App\Http\Controllers\AdminController::class, 'updateBatiment'])->name('admin.batiment.update');
    Route::post('/admin/batiment/{id}/delete', [App\Http\Controllers\AdminController::class, 'deleteBatiment'])->name('admin.batiment.delete');

    // Abonnement client
    Route::get('/client/abonnement', [ClientAbonnementController::class, 'show'])->name('client.abonnement.show');
    Route::get('/client/abonnement/success/{abonnement}', [ClientAbonnementController::class, 'success'])->name('client.abonnement.success');
    Route::post('/client/abonnement/choisir', [App\Http\Controllers\ClientAbonnementController::class, 'choisirAbonnement'])->name('client.abonnement.choisir');
    Route::get('/client/abonnement', [App\Http\Controllers\ClientAbonnementController::class, 'index'])->name('client.abonnement');
});

Route::get('/pdf/paiement/{id}', [PdfController::class, 'paiement'])->name('pdf.paiement');
Route::get('/pdf/livraison/{id}', [PdfController::class, 'livraison'])->name('pdf.livraison');

Route::get('/base', function () {
    return view('base');
})->name('base');

Route::get('/commerces', [App\Http\Controllers\CommercantController::class, 'index'])->name('commerces.index');
Route::get('/annonces', [App\Http\Controllers\ClientController::class, 'annoncesGlobales'])->name('annonces.index');
Route::get('/annonces/prestations', [App\Http\Controllers\AnnoncePrestationController::class, 'index'])->name('annonces.prestations');
Route::get('/livraisons', [App\Http\Controllers\LivreurController::class, 'mesLivraisons'])->name('livraisons.index');
Route::get('/commerces/{id}/produits', [App\Http\Controllers\CommercantController::class, 'produitsCommerce'])->name('commerces.produits');

Route::post('/annonces/acheter', [App\Http\Controllers\ClientController::class, 'acheterAnnonce'])->name('annonces.acheter');
Route::get('/annonces/success/{annonce}', [App\Http\Controllers\ClientController::class, 'successAchat'])->name('annonces.success');
Route::get('/annonces/acheter/{annonce}', [App\Http\Controllers\ClientController::class, 'afficherAchat'])->name('annonces.acheter');
Route::get('/annonces/pdf/{annonce}', [App\Http\Controllers\ClientController::class, 'pdfAchat'])->name('annonces.pdf');
Route::post('/annonces/{annonce}/payment-intent', [AnnonceController::class, 'paymentIntent'])->name('annonces.payment_intent');
Route::post('/annonces/{annonce}/payer', [AnnonceController::class, 'payer'])->name('annonces.payer');
Route::get('/annonces/{annonce}/payer', [App\Http\Controllers\AnnonceController::class, 'stripePayer'])->name('annonces.stripe_payer');
Route::get('/annonces/{annonce}/paiement/success', [App\Http\Controllers\AnnonceController::class, 'stripeSuccess'])->name('annonces.stripe_success');
Route::post('/annonces/{annonce}/stripe-intent', [AnnonceController::class, 'stripeIntent'])->name('annonces.stripe_intent');

Route::post('/panier/ajouter/{id}', [App\Http\Controllers\PanierController::class, 'ajouter'])->name('panier.ajouter');
Route::get('/panier', [App\Http\Controllers\PanierController::class, 'afficher'])->name('panier.afficher');
Route::post('/panier/supprimer/{id}', [App\Http\Controllers\PanierController::class, 'supprimer'])->name('panier.supprimer');
Route::get('/panier/paiement', [App\Http\Controllers\PanierController::class, 'paiement'])->name('panier.paiement');
Route::post('/panier/payer', [App\Http\Controllers\PanierController::class, 'payer'])->name('panier.payer');
Route::post('/panier/payment-intent', [App\Http\Controllers\PanierController::class, 'createPaymentIntent'])->name('panier.payment_intent');
Route::get('/panier/stripe', [App\Http\Controllers\PanierController::class, 'stripePayer'])->name('panier.stripe_payer');
Route::get('/panier/stripe/success', [App\Http\Controllers\PanierController::class, 'stripeSuccess'])->name('panier.stripe_success');

Route::get('/livreur/deplacements', [App\Http\Controllers\LivreurController::class, 'deplacements'])->name('livreur.deplacements');
Route::get('/livreur/deplacement/{id}/livraisons', [App\Http\Controllers\LivreurController::class, 'livraisonsPourDeplacement'])->name('livreur.deplacement.livraisons');
Route::get('/livreur/deplacement/creer', [App\Http\Controllers\LivreurController::class, 'createDeplacement'])->name('livreur.deplacement.create');
Route::post('/livreur/deplacement/creer', [App\Http\Controllers\LivreurController::class, 'storeDeplacement'])->name('livreur.deplacement.store');
Route::get('/livreur/deplacement/{id}/modifier', [App\Http\Controllers\LivreurController::class, 'editDeplacement'])->name('livreur.deplacement.edit');
Route::post('/livreur/deplacement/{id}/modifier', [App\Http\Controllers\LivreurController::class, 'updateDeplacement'])->name('livreur.deplacement.update');
Route::post('/livreur/deplacement/{id}/supprimer', [App\Http\Controllers\LivreurController::class, 'deleteDeplacement'])->name('livreur.deplacement.delete');
Route::post('/livreur/livraison/{id}/prendre', [App\Http\Controllers\LivreurController::class, 'prendreLivraison'])->name('livreur.prendre_livraison');

// Paiement abonnement client
Route::post('/client/abonnement/paiement', [App\Http\Controllers\ClientAbonnementController::class, 'paiement'])->name('client.abonnement.paiement');
Route::get('/client/abonnement/paiement/success', [App\Http\Controllers\ClientAbonnementController::class, 'paiementSuccess'])->name('client.abonnement.paiement.success');

Route::get('/livreur/livraisons/prendre', [App\Http\Controllers\LivreurController::class, 'prendreLivraisons'])->name('livreur.deliveries.prendre_liste');
Route::post('/livreur/livraisons/{id}/prendre', [App\Http\Controllers\LivreurController::class, 'prendreLivraison'])->name('livreur.deliveries.prendre');

// Création d'annonce prestation par le prestataire
Route::get('/prestataire/annonce-prestation/create', [App\Http\Controllers\AnnoncePrestationController::class, 'create'])->name('prestataire.annonce_prestation.create');
Route::post('/prestataire/annonce-prestation', [App\Http\Controllers\AnnoncePrestationController::class, 'store'])->name('prestataire.annonce_prestation.store');

// Prendre une annonce prestation
Route::get('/annonces/prestations/{id}/prendre', [App\Http\Controllers\AnnoncePrestationController::class, 'prendre'])->name('annonces.prestations.prendre');
Route::post('/annonces/prestations/{id}/payer', [App\Http\Controllers\AnnoncePrestationController::class, 'payer'])->name('annonces.prestations.payer');
Route::get('/annonces/prestations/{reservation}/success', [App\Http\Controllers\AnnoncePrestationController::class, 'success'])->name('annonces.prestations.success');
