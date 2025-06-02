<?php 
session_start();
if (!isset($_SESSION['type_utilisateur']) || $_SESSION['type_utilisateur'] != 'admin') {
    die("Accès refusé");
}

include 'db.php';

$search = isset($_GET['search']) ? trim($_GET['search']) : '';

if (!empty($search)) {
    $users = $api_client->get('users', ['search' => $search]);
    $users = $users['data'] ?? [];
} else {
    $users = $api_client->get('users');
    $users = $users['data'] ?? [];
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des utilisateurs</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<?php
include 'header.php';
?>

<body class="bg-gray-100">
    <div class="container mx-auto px-4 py-8">
        <h1 class="text-2xl font-bold text-gray-800 mb-6">Gestion des utilisateurs</h1>

        <form method="GET" action="" class="mb-6">
            <input
                class="px-8 bg-zinc-200 text-zinc-600 font-mono ring-1 ring-zinc-400 focus:ring-2 focus:ring-green-400 outline-none duration-300 placeholder:text-zinc-600 placeholder:opacity-50 rounded-full py-1 shadow-md focus:shadow-lg focus:shadow-green-400 w-full md:w-1/2"
                autocomplete="off"
                placeholder="Rechercher par Nom/Prénom/Email"
                name="search"
                type="text"
                value="<?= htmlspecialchars($search) ?>"
            />
        </form>

        
        <div class="overflow-x-auto bg-white rounded-lg shadow">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nom</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Prénom</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Statut</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php foreach ($users as $user): ?>
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?= $user['id_utilisateur'] ?></td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?= $user['nom'] ?? 'Non défini' ?></td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?= $user['prenom'] ?? 'Non défini' ?></td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?= $user['email'] ?></td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                    <?= $user['type_utilisateur'] == 'admin' ? 'bg-purple-100 text-purple-800' : 
                                       ($user['type_utilisateur'] == 'livreur' ? 'bg-green-100 text-green-800' : 
                                       ($user['type_utilisateur'] == 'prestataire' ? 'bg-blue-100 text-blue-800' : 
                                       ($user['type_utilisateur'] == 'commercant' ? 'bg-yellow-100 text-yellow-800' : 
                                       'bg-gray-100 text-gray-800'))) ?>">
                                    <?= ucfirst($user['type_utilisateur']) ?>
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <?php if ($user['statut_compte'] == 'banni'): ?>
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">Banni</span>
                                <?php else: ?>
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Actif</span>
                                <?php endif; ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-1">
                                <!-- Bouton pour changer le type d'utilisateur -->
                                <div class="inline-block relative">
                                    <button class="inline-flex items-center px-2.5 py-1.5 border border-transparent text-xs font-medium rounded text-white bg-blue-600 hover:bg-blue-700">
                                        Changer type
                                    </button>
                                    <div class="absolute hidden group-hover:block bg-white shadow-lg rounded-md mt-2 right-0 w-48 z-20 border">
                                        <a href="status.php?id=<?= $user['id_utilisateur'] ?>&type=admin" class="block px-4 py-2 hover:bg-gray-100 text-sm">Admin</a>
                                        <a href="status.php?id=<?= $user['id_utilisateur'] ?>&type=livreur" class="block px-4 py-2 hover:bg-gray-100 text-sm">Livreur</a>
                                        <a href="status.php?id=<?= $user['id_utilisateur'] ?>&type=prestataire" class="block px-4 py-2 hover:bg-gray-100 text-sm">Prestataire</a>
                                        <a href="status.php?id=<?= $user['id_utilisateur'] ?>&type=commercant" class="block px-4 py-2 hover:bg-gray-100 text-sm">Commerçant</a>
                                        <a href="status.php?id=<?= $user['id_utilisateur'] ?>&type=client" class="block px-4 py-2 hover:bg-gray-100 text-sm">Client</a>
                                    </div>
                                </div>
                                
                                <!-- Bouton pour bannir/débannir -->
                                <?php if ($user['statut_compte'] == 'banni'): ?>
                                    <a href="ban.php?id=<?= $user['id_utilisateur'] ?>&action=unban" class="inline-flex items-center px-2.5 py-1.5 border border-transparent text-xs font-medium rounded text-white bg-green-600 hover:bg-green-700">
                                        Débannir
                                    </a>
                                <?php else: ?>
                                    <a href="ban.php?id=<?= $user['id_utilisateur'] ?>&action=ban" class="inline-flex items-center px-2.5 py-1.5 border border-transparent text-xs font-medium rounded text-white bg-red-600 hover:bg-red-700">
                                        Bannir
                                    </a>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
