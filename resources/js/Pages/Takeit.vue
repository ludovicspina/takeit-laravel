<template>
    <div class="p-8 space-y-8">
        <RecapBar :client="selectedClient" :material="selectedMaterial" :step="step" />

        <!-- Étape 1 : Client -->
        <div v-if="step === 1" class="space-y-4 max-w-md">
            <h2 class="text-2xl font-bold mb-4">1️⃣ Sélection ou création du client</h2>

            <div>
                <label class="block text-sm font-medium text-gray-700">Rechercher un client</label>
                <input
                    type="text"
                    v-model="search"
                    @input="searchClients"
                    placeholder="Nom, prénom, société..."
                    class="mt-1 block w-full border rounded p-2"
                />
                <ul v-if="results.length" class="border rounded bg-white mt-2 max-h-60 overflow-y-auto">
                    <li
                        v-for="client in results"
                        :key="client.rowid"
                        @click="selectClient(client)"
                        class="px-4 py-2 hover:bg-blue-100 cursor-pointer"
                    >
                        <strong>{{ client.nom }}</strong><br />
                        <small>{{ client.address }}, {{ client.zip }} {{ client.town }} - 📞 {{ client.phone }}</small>
                    </li>
                    <li class="px-4 py-2 bg-yellow-100 text-sm text-center cursor-pointer hover:bg-yellow-200" @click="forceCreate = true">
                        ➕ Créer un nouveau client malgré les résultats
                    </li>
                </ul>
            </div>

            <div v-if="search.length > 2 && (results.length === 0 || forceCreate)" class="mt-4 border-t pt-4">
                <h2 class="font-bold mb-2">Créer un nouveau client</h2>
                <div class="space-y-2">
                    <input v-model="newClient.name" type="text" placeholder="Nom" class="w-full border rounded p-2" />
                    <input v-model="newClient.address" type="text" placeholder="Adresse" class="w-full border rounded p-2" />
                    <input v-model="newClient.zip" type="text" placeholder="Code postal" class="w-full border rounded p-2" />
                    <input v-model="newClient.town" type="text" placeholder="Ville" class="w-full border rounded p-2" />
                    <input v-model="newClient.email" type="email" placeholder="Email" class="w-full border rounded p-2" />
                    <input v-model="newClient.phone" type="text" placeholder="Téléphone" class="w-full border rounded p-2" />
                    <button @click="createClient" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
                        Créer le client
                    </button>
                </div>
            </div>

            <div v-if="selectedClient" class="p-4 border rounded bg-gray-50">
                <p class="font-semibold">{{ selectedClient.nom }}</p>
                <p>{{ selectedClient.address }}, {{ selectedClient.zip }} {{ selectedClient.town }}</p>
                <p>📞 {{ selectedClient.phone }}</p>
            </div>

            <button
                v-if="selectedClient"
                @click="step = 2"
                class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700"
            >
                Continuer vers l'étape suivante ➡️
            </button>
        </div>

        <!-- Étape 2 : Matériel -->
        <div v-if="step === 2" class="space-y-4 max-w-md">
            <h2 class="text-2xl font-bold mb-4">2️⃣ Matériel à déposer</h2>

            <div>
                <label class="block text-sm">Type</label>
                <select v-model="material.type" class="w-full border rounded p-2">
                    <option disabled value="">Sélectionner un type</option>
                    <option>Ordinateur</option>
                    <option>Imprimante</option>
                    <option>Écran</option>
                    <option>Scanner</option>
                    <option>Autre</option>
                </select>
            </div>

            <div>
                <label class="block text-sm">Marque</label>
                <input v-model="material.marque" type="text" class="w-full border rounded p-2" />
            </div>

            <div>
                <label class="block text-sm">Modèle</label>
                <input v-model="material.modele" type="text" class="w-full border rounded p-2" />
            </div>

            <div>
                <label class="block text-sm">Numéro de série</label>
                <input v-model="material.numero_serie" type="text" class="w-full border rounded p-2" />
            </div>

            <div>
                <label class="block text-sm">Accessoires fournis</label>
                <textarea v-model="material.accessoires" class="w-full border rounded p-2" rows="2"></textarea>
            </div>

            <div>
                <label class="block text-sm">État visuel du matériel</label>
                <textarea v-model="material.etat" class="w-full border rounded p-2" rows="2"></textarea>
            </div>

            <div>
                <label class="block text-sm">Mot de passe</label>
                <input v-model="material.mot_de_passe" type="text" class="w-full border rounded p-2" />
            </div>


            <!-- Liste des matériels existants -->
            <div v-if="materielsExistants.length" class="border-t pt-4">
                <h3 class="font-semibold mb-2 text-sm">📦 Matériels déjà enregistrés :</h3>
                <ul class="space-y-2">
                    <li
                        v-for="m in materielsExistants"
                        :key="m.id"
                        @click="fillMaterialFromExisting(m)"
                        class="p-2 bg-gray-100 rounded hover:bg-blue-100 cursor-pointer text-sm"
                    >
                        <strong>{{ m.type }}</strong> - {{ m.marque }} {{ m.modele }} <br />
                        SN : {{ m.numero_serie }} <br />
                        <span class="text-xs text-gray-600">{{ m.accessoires }}</span>
                    </li>
                </ul>
            </div>


            <button
                @click="handleMaterialNext"
                class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700"
            >
                Étape suivante : Détails de la panne
            </button>
        </div>

        <!-- Étape 3 : Détails de la panne -->
        <div v-if="step === 3" class="space-y-4 max-w-md">
            <h2 class="text-2xl font-bold mb-4">3️⃣ Détails de la panne</h2>

            <div>
                <label class="block text-sm">Description de la panne</label>
                <textarea v-model="panne.description" class="w-full border rounded p-2" rows="3"></textarea>
            </div>

            <div>
                <label class="block text-sm">Remarques additionnelles</label>
                <textarea v-model="panne.remarques" class="w-full border rounded p-2" rows="2"></textarea>
            </div>

            <div>
                <label class="block text-sm">Services à effectuer</label>
                <div class="grid grid-cols-2 gap-2 mt-2">
                    <div
                        v-for="service in services"
                        :key="service.id"
                        class="flex items-center gap-2"
                    >
                        <input
                            type="checkbox"
                            :value="service.id"
                            v-model="selectedServices"
                            class="accent-blue-600"
                        />
                        <span class="text-sm">{{ service.nom }}</span>
                    </div>
                </div>
            </div>

            <div>
                <label class="block text-sm">Démarrage PC</label>
                <select v-model="panne.demarrage_pc" class="w-full border rounded p-2">
                    <option :value="true">Oui</option>
                    <option :value="false">Non</option>
                </select>
            </div>

            <div>
                <label class="block text-sm">Démarrage OS</label>
                <select v-model="panne.demarrage_os" class="w-full border rounded p-2">
                    <option :value="true">Oui</option>
                    <option :value="false">Non</option>
                </select>
            </div>

            <div>
                <label class="block text-sm">Dommages matériels</label>
                <textarea v-model="panne.dommages" class="w-full border rounded p-2" rows="2"></textarea>
            </div>

            <div>
                <label class="block text-sm">Matériel déposé</label>
                <textarea v-model="panne.materiel_depose" class="w-full border rounded p-2" rows="2"></textarea>
            </div>



            <button
                @click="handleFinalSubmit"
                class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700"
            >
                ✅ Terminer la prise en charge
            </button>
        </div>


    </div>
</template>

<script setup>
import { ref, watch, onMounted } from 'vue'
import axios from 'axios'
import RecapBar from '@/Components/RecapBar.vue'

const step = ref(1)

const search = ref('')
const results = ref([])
const selectedClient = ref(null)
const selectedMaterial = ref(null) // pour plus tard

const newClient = ref({
    name: '',
    address: '',
    zip: '',
    town: '',
    email: '',
    phone: '',
})

const forceCreate = ref(false)

const searchClients = async () => {
    if (search.value.length < 2) {
        results.value = []
        return
    }

    const { data } = await axios.get('/api/clients', {
        params: { q: search.value }
    })
    results.value = data
    newClient.value.name = search.value
}

const selectClient = (client) => {
    selectedClient.value = client
    results.value = []
    search.value = client.nom
}

const createClient = async () => {
    try {
        const { data } = await axios.post('/api/clients', newClient.value)
        selectedClient.value = data
        search.value = data.name
        results.value = []
        forceCreate.value = false
        alert('Client créé avec succès !')
    } catch (error) {
        alert('Erreur : ' + JSON.stringify(error.response?.data || {}, null, 2))
    }
}

const material = ref({
    type: '',
    marque: '',
    modele: '',
    numero_serie: '',
    accessoires: '',
    etat: '',
    mot_de_passe: '',
})

const handleMaterialNext = async () => {
    if (!material.value.type) return alert("Merci de sélectionner un type de matériel.")

    try {
        const { data } = await axios.post('/api/materiels', {
            client_id: selectedClient.value.rowid,
            ...material.value
        })
        selectedMaterial.value = data
        step.value = 3
    } catch (error) {
        alert("Erreur matériel : " + (error.response?.data?.message || 'inconnue'))
    }
}

const panne = ref({
    description: '',
    remarques: '',
    demarrage_pc: false,
    demarrage_os: false,
    dommages: '',
    materiel_depose: '',
})

const handleFinalSubmit = async () => {
    if (!panne.value.description) return alert("Merci de décrire la panne.")

    const payload = {
        client_id: selectedClient.value.rowid,
        ...material.value,
        ...panne.value,
        services: selectedServices.value
    }

    try {
        const { data } = await axios.post('/api/prises-en-charge', payload)
        alert('Prise en charge enregistrée avec succès !')
        // Reset ou redirection possible ici
    } catch (err) {
        alert("Erreur lors de l'enregistrement : " + (err.response?.data?.message || 'inconnue'))
    }
}

const materielsExistants = ref([])

watch(selectedClient, async (client) => {
    if (client?.rowid) {
        const { data } = await axios.get(`/api/materiels/${client.rowid}`)
        materielsExistants.value = data
    }
}, { immediate: true })

const fillMaterialFromExisting = (m) => {
    material.value = {
        type: m.type,
        marque: m.marque,
        modele: m.modele,
        numero_serie: m.numero_serie,
        accessoires: m.accessoires,
        etat: m.etat
    }
}

const services = ref([])
const selectedServices = ref([])

onMounted(async () => {
    const { data } = await axios.get('/api/services')
    services.value = data
})


</script>
