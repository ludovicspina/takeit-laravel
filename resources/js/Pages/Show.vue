<template>
    <div class="p-8">
        <a href="/depannages" class="text-blue-600 underline hover:text-blue-800 mb-4 inline-block">⬅️ Retour à la liste</a>

        <h1 class="text-3xl font-bold mb-6">🔍 Détail du dépannage #{{ depannage.id }}</h1>
        <div class="space-y-2">
            <p><strong>Client :</strong> {{ depannage.client_nom ?? 'Inconnu' }}</p>
            <p><strong>Adresse :</strong> {{ depannage.client_adresse }}, {{ depannage.client_zip }} {{ depannage.client_ville }}</p>
            <p><strong>📞 Téléphone :</strong> {{ depannage.client_tel }}</p>
            <p><strong>📧 Email :</strong> {{ depannage.client_email }}</p>
            <hr class="my-4" />
            <p><strong>Matériel :</strong> {{ depannage.type }} — {{ depannage.marque }} {{ depannage.modele }}</p>
            <p><strong>Mot de passe :</strong> 🔒 {{ depannage.mot_de_passe || 'Non fourni' }}</p>
            <p><strong>Accessoires :</strong> {{ depannage.materiel_depose }}</p>
            <hr class="my-4" />
            <p><strong>📄 Description de la panne :</strong> {{ depannage.description }}</p>
            <p><strong>Remarques :</strong> {{ depannage.remarques }}</p>
            <p><strong>Dommages :</strong> {{ depannage.dommages }}</p>
            <p><strong>Démarrage PC :</strong> {{ depannage.demarrage_pc ? 'Oui' : 'Non' }}</p>
            <p><strong>Démarrage OS :</strong> {{ depannage.demarrage_os ? 'Oui' : 'Non' }}</p>
            <p><strong>📆 Créé le :</strong> {{ formatDate(depannage.created_at) }}</p>
            <p><strong>Statut :</strong> {{ depannage.statut }}</p>
        </div>
        <div class="space-y-2">
        </div>
    </div>
</template>

<script setup>
defineProps({ depannage: Object })

function formatDate(date) {
    return new Date(date).toLocaleDateString('fr-FR')
}
</script>
