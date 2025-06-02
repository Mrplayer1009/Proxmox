<?php
session_start();
require_once '../php/verif.php';
require_once '../php/api_client.php';

if (!isset($_SESSION['user_id']) || !in_array('livreur', $_SESSION['roles'])) {
    header('Location: ../index.php');
    exit;
}

$userId = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['document'])) {
    $uploadDir = '../uploads/documents/';
    
    if (!file_exists($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }
    
    $fileName = basename($_FILES['document']['name']);
    $targetFilePath = $uploadDir . $userId . '_' . $fileName;
    $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);
    
    $allowTypes = array('jpg', 'png', 'jpeg', 'pdf', 'doc', 'docx');
    if (in_array($fileType, $allowTypes)) {
        if (move_uploaded_file($_FILES['document']['tmp_name'], $targetFilePath)) {
            $data = [
                'user_id' => $userId,
                'type' => $_POST['type'],
                'nom' => $fileName,
                'chemin' => $targetFilePath,
                'date_upload' => date('Y-m-d H:i:s'),
                'statut' => 'pending'
            ];
            
            $result = $api->post('document', $data);
            
            if ($result && isset($result['id'])) {
                $successMessage = "Le document a été téléchargé avec succès et est en attente de validation.";
            } else {
                $errorMessage = "Erreur lors de l'enregistrement du document.";
            }
        } else {
            $errorMessage = "Désolé, une erreur s'est produite lors du téléchargement de votre fichier.";
        }
    } else {
        $errorMessage = "Désolé, seuls les fichiers JPG, JPEG, PNG, PDF, DOC et DOCX sont autorisés.";
    }
}

// Get documents from API
$documents = $api->get("document?user_id=$userId");

include 'header_livreur.php';
?>

<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-6">Mes Documents</h1>
    
    <?php if (isset($successMessage)): ?>
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4" role="alert">
            <p><?= $successMessage ?></p>
        </div>
    <?php endif; ?>
    
    <?php if (isset($errorMessage)): ?>
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4" role="alert">
            <p><?= $errorMessage ?></p>
        </div>
    <?php endif; ?>
    
    <div class="bg-white rounded-lg shadow-md p-6 mb-8">
        <h2 class="text-xl font-semibold mb-4">Télécharger un nouveau document</h2>
        <form method="POST" enctype="multipart/form-data">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label for="type" class="block text-gray-700 text-sm font-bold mb-2">Type de document</label>
                    <select id="type" name="type" required
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                        <option value="">Sélectionnez un type</option>
                        <option value="Carte d'identité">Carte d'identité</option>
                        <option value="Permis de conduire">Permis de conduire</option>
                        <option value="Assurance">Assurance</option>
                        <option value="Carte grise">Carte grise</option>
                        <option value="Autre">Autre</option>
                    </select>
                </div>
                
                <div>
                    <label for="document" class="block text-gray-700 text-sm font-bold mb-2">Fichier</label>
                    <input type="file" id="document" name="document" required
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                    <p class="text-xs text-gray-500 mt-1">Formats acceptés: JPG, PNG, PDF, DOC, DOCX</p>
                </div>
            </div>
            
            <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                Télécharger le document
            </button>
        </form>
    </div>
    
    <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-xl font-semibold mb-4">Documents téléchargés</h2>
        
        <?php if (empty($documents)): ?>
            <p class="text-gray-500">Vous n'avez pas encore téléchargé de documents.</p>
        <?php else: ?>
            <div class="overflow-x-auto">
                <table class="min-w-full bg-white">
                    <thead>
                        <tr>
                            <th class="py-2 px-4 border-b border-gray-200 bg-gray-50 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Type</th>
                            <th class="py-2 px-4 border-b border-gray-200 bg-gray-50 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Nom</th>
                            <th class="py-2 px-4 border-b border-gray-200 bg-gray-50 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Date</th>
                            <th class="py-2 px-4 border-b border-gray-200 bg-gray-50 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Statut</th>
                            <th class="py-2 px-4 border-b border-gray-200 bg-gray-50 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($documents as $document): ?>
                            <tr>
                                <td class="py-2 px-4 border-b border-gray-200"><?= htmlspecialchars($document['type']) ?></td>
                                <td class="py-2 px-4 border-b border-gray-200"><?= htmlspecialchars($document['nom']) ?></td>
                                <td class="py-2 px-4 border-b border-gray-200"><?= date('d/m/Y', strtotime($document['date_upload'])) ?></td>
                                <td class="py-2 px-4 border-b border-gray-200">
                                    <?php if ($document['statut'] === 'approved'): ?>
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                            Approuvé
                                        </span>
                                    <?php elseif ($document['statut'] === 'pending'): ?>
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                            En attente
                                        </span>
                                    <?php else: ?>
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                            Refusé
                                        </span>
                                    <?php endif; ?>
                                </td>
                                <td class="py-2 px-4 border-b border-gray-200">
                                    <a href="../uploads/<?php echo $document['fichier']; ?>" target="_blank" class="text-blue-600 hover:text-blue-900 mr-3">
                                        <i class="fas fa-eye"></i> Voir
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php include '../php/footer.php'; ?>
