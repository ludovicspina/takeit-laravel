<template>
    <div class="p-8 space-y-8">
        <a href="/"
           class="bg-white shadow rounded-lg px-4 py-2 border hover:border-blue-500 hover:bg-blue-50 transition cursor-pointer">🏠 Menu</a>

        <RecapBar :client="selectedClient" :material="selectedMaterial" :step="step"/>

        <!-- Étape 1 : Client -->
        <div v-if="step === 1" class="space-y-4 max-w-md">
            <h2 class="text-2xl font-bold mb-4">1️⃣ Sélection ou création du client</h2>

            <div v-if="!forceCreate">
                <label class="block text-sm font-medium text-gray-700">Rechercher un client</label>
                <input
                    type="text"
                    v-model="search"
                    @input="searchClients"
                    placeholder="Nom, prénom, société..."
                    class="mt-1 block w-full border rounded p-2"
                />
                <ul v-if="results.length" class="border rounded bg-white mt-2 max-h-60 overflow-y-auto">
                    <li class="px-4 py-2 bg-yellow-100 text-sm text-center cursor-pointer hover:bg-yellow-200"
                        @click="forceCreate = true">
                        ➕ Créer un nouveau client
                    </li>
                    <li
                        v-for="client in results"
                        :key="client.rowid"
                        @click="selectClient(client)"
                        class="px-4 py-2 hover:bg-blue-100 cursor-pointer"
                    >
                        <strong>{{ client.nom }}</strong><br/>
                        <small>{{ client.address }}, {{ client.zip }} {{ client.town }} - 📞 {{ client.phone }}</small>
                    </li>
                </ul>
            </div>

            <div v-if="forceCreate" class="mt-4 border-t pt-4">
                <h2 class="font-bold mb-2">Créer un nouveau client</h2>
                <div class="space-y-2">
                    <input v-model="newClient.name" type="text" placeholder="Nom" class="w-full border rounded p-2"/>
                    <input v-model="newClient.address" type="text" placeholder="Adresse"
                           class="w-full border rounded p-2"/>
                    <input v-model="newClient.zip" type="text" placeholder="Code postal"
                           class="w-full border rounded p-2"/>
                    <input v-model="newClient.town" type="text" placeholder="Ville" class="w-full border rounded p-2"/>
                    <input v-model="newClient.email" type="email" placeholder="Email"
                           class="w-full border rounded p-2"/>
                    <input v-model="newClient.phone" type="text" placeholder="Téléphone"
                           class="w-full border rounded p-2"/>
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
            <button
                @click="step = 1"
                class="bg-gray-200 text-gray-700 px-4 py-2 rounded hover:bg-gray-300"
            >
                ⬅️ Retour à l’étape précédente
            </button>
            <h2 class="text-2xl font-bold mb-4">2️⃣ Matériel à déposer</h2>

            <div>
                <label class="block text-sm">Type</label>
                <select v-model="material.type" class="w-full border rounded p-2">
                    <option disabled value="">Sélectionner un type</option>
                    <option>PC Portable</option>
                    <option>PC Fixe</option>
                    <option>Tablette</option>
                    <option>Imprimante</option>
                    <option>Stockage externe (HDD externe, clé USB, carte SD, NAS, ...)</option>
                    <option>Autre matériel</option>
                </select>
            </div>

            <div>
                <label class="block text-sm">Référence matériel</label>
                <input v-model="material.marque" type="text" class="w-full border rounded p-2"/>
            </div>

            <div>
                <label class="block text-sm">Mot de passe</label>
                <input v-model="material.mot_de_passe" type="text" class="w-full border rounded p-2"/>
            </div>
            <button
                type="button"
                @click="addMaterial"
                class="bg-gray-100 border rounded px-3 py-1 hover:bg-gray-200"
            >
                ➕ Ajouter ce matériel
            </button>

            <ul>
                <li
                    v-for="(m, index) in materiels"
                    :key="m.id"
                    class="text-sm bg-gray-50 border rounded p-2"
                    :class="{ 'border-blue-500 bg-blue-100': selectedMaterial?.id === m.id }"
                >
                    <strong>{{ m.type }}</strong> — {{ m.marque }} {{ m.modele }}<br />
                    SN: {{ m.numero_serie }} | 🔒 {{ m.mot_de_passe }}

                    <div class="flex gap-2 mt-2">
                        <button
                            @click="selectedMaterial = m"
                            class="text-sm text-blue-600 underline hover:text-blue-800"
                        >
                            🎯 Sélectionner ce matériel
                        </button>
                        <button
                            @click="deleteMaterial(m.id, index)"
                            class="text-red-500 text-xs"
                        >
                            🗑 Supprimer
                        </button>
                    </div>
                </li>
            </ul>



            <button
                @click="step = 3"
                :disabled="!selectedMaterial"
                class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 disabled:bg-gray-300 disabled:cursor-not-allowed"
            >
                Étape suivante : Détails de la panne
            </button>

        </div>

        <!-- Étape 3 : Détails de la panne -->


        <div v-if="step === 3" class="space-y-4 max-w-md">
            <button
                @click="step = 2"
                class="bg-gray-200 text-gray-700 px-4 py-2 rounded hover:bg-gray-300"
            >
                ⬅️ Retour à l’étape précédente
            </button>
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


            <fieldset>
                <label class="block text-sm mb-1">Démarrage PC</label>
                <div class="flex gap-4">
                    <label class="flex items-center gap-1">
                        <input
                            type="radio"
                            name="pc"
                            v-model="panne.demarrage_pc"
                            :value="true"
                        />
                        Oui
                    </label>
                    <label class="flex items-center gap-1">
                        <input
                            type="radio"
                            name="pc"
                            v-model="panne.demarrage_pc"
                            :value="false"
                        />
                        Non
                    </label>
                </div>
            </fieldset>


            <fieldset>
                <label class="block text-sm mb-1">Démarrage OS</label>
                <div class="flex gap-4">
                    <label class="flex items-center gap-1">
                        <input
                            type="radio"
                            name="os"
                            v-model="panne.demarrage_os"
                            :value="true"
                        />
                        Oui
                    </label>
                    <label class="flex items-center gap-1">
                        <input
                            type="radio"
                            name="os"
                            v-model="panne.demarrage_os"
                            :value="false"
                        />
                        Non
                    </label>
                </div>
            </fieldset>

            <div>
                <label class="block text-sm">Dommages matériels</label>
                <textarea v-model="panne.dommages" class="w-full border rounded p-2" rows="2"></textarea>
            </div>

            <div class="grid grid-cols-2 gap-2">
                <label
                    v-for="option in optionsMaterielDepose"
                    :key="option"
                    class="flex items-center gap-2"
                >
                    <input
                        type="checkbox"
                        :value="option"
                        v-model="materielDeposeSelection"
                        class="accent-blue-600"
                    />
                    {{ option }}
                </label>

                <!-- Champ "Autre" visible uniquement si sélectionné -->
                <div v-if="materielDeposeSelection.includes('Autre')" class="col-span-2 space-y-2 mt-2">
                    <label class="block text-sm mb-1">Précisez les objets déposés</label>

                    <div
                        v-for="(item, index) in autresMateriels"
                        :key="index"
                        class="flex gap-2"
                    >
                        <input
                            v-model="autresMateriels[index]"
                            type="text"
                            placeholder="Ex : Clé USB Star Wars"
                            class="w-full border rounded p-2"
                        />
                        <button
                            @click="autresMateriels.splice(index, 1)"
                            type="button"
                            class="text-red-500 font-bold"
                            title="Supprimer ce champ"
                        >
                            ✖
                        </button>
                    </div>

                    <button
                        type="button"
                        @click="autresMateriels.push('')"
                        class="text-sm text-blue-600 underline hover:text-blue-800"
                    >
                        ➕ Ajouter un autre objet
                    </button>
                </div>

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
import {ref, watch, onMounted} from 'vue'
import axios from 'axios'
import RecapBar from '@/Components/RecapBar.vue'

const step = ref(1)

let search = ref('')
let results = ref([])
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

const optionsMaterielDepose = [
    'PC',
    'Câble',
    'Chargeur',
    'Souris',
    'Clavier',
    'Sacoche',
    'Disque dur',
    'Autre'
]

const materielDeposeSelection = ref([])

const forceCreate = ref(false)

let debounceTimer = null

const searchClients = async () => {
    clearTimeout(debounceTimer)

    debounceTimer = setTimeout(async () => {
        if (search.value.length < 2) {
            results.value = []
            return
        }

        const { data } = await axios.get('/api/clients', {
            params: { q: search.value }
        })

        results.value = data
        newClient.value.name = search.value
    }, 200) // ⏱️ délai de 0.5 seconde
}

const selectClient = (client) => {
    selectedClient.value = client
    results.value = []
    search.value = client.nom

    search.value = ""
    results.value = []
}

const createClient = async () => {
    try {
        const { data: created } = await axios.post('/api/clients', newClient.value)

        // Utilise l'ID retourné par Dolibarr
        const { data: fullClient } = await axios.get(`/api/client/${created.id}`)

        selectedClient.value = fullClient

        // Reset des champs
        newClient.value = {
            name: '',
            address: '',
            zip: '',
            town: '',
            email: '',
            phone: '',
        }
        search.value = ''
        forceCreate.value = false
        results.value = []

        alert('Client créé et sélectionné avec succès !')
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


const materiels = ref([]) // tableau de matériels ajoutés


watch(selectedClient, async (client) => {
    if (client?.rowid) {
        const { data } = await axios.get(`/api/materiels/${client.rowid}`)
        materiels.value = data
    }
}, { immediate: true })

const addMaterial = async () => {
    if (!material.value.type || !material.value.marque) {
        return alert("Merci de renseigner au moins le type et la marque du matériel.")
    }

    try {
        const { data } = await axios.post('/api/materiels', {
            client_id: selectedClient.value.rowid,
            ...material.value
        })

        // Ajoute au tableau local pour affichage
        materiels.value.push(data)

        // Reset
        material.value = {
            type: '',
            marque: '',
            modele: '',
            numero_serie: '',
            accessoires: '',
            etat: '',
            mot_de_passe: ''
        }
    } catch (error) {
        alert("Erreur lors de l'ajout du matériel : " + (error.response?.data?.message || 'inconnue'))
    }
}



const autreMateriel = ref('')
const autresMateriels = ref(['']) // tableau de champs texte

const handleMaterialNext = async () => {
    if (!material.value.type) return alert("Merci de sélectionner un type de matériel.")

    try {
        const {data} = await axios.post('/api/materiels', {
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
    demarrage_pc: true,
    demarrage_os: true,
    dommages: '',
    materiel_depose: '',
})

const deleteMaterial = async (id, index) => {
    if (!confirm("Supprimer ce matériel ?")) return

    try {
        await axios.delete(`/api/materiels/${id}`)
        materiels.value.splice(index, 1)
    } catch (err) {
        alert("Erreur lors de la suppression : " + (err.response?.data?.message || 'inconnue'))
    }
}


const handleFinalSubmit = async () => {
    if (!panne.value.description) return alert("Merci de décrire la panne.")

// Remplace "Autre" par le contenu du champ texte, s’il y en a un
    let deposeFinal = [...materielDeposeSelection.value]
    const index = deposeFinal.indexOf('Autre')

    if (index !== -1) {
        // Supprime "Autre"
        deposeFinal.splice(index, 1)
        // Ajoute les éléments non vides
        deposeFinal = deposeFinal.concat(
            autresMateriels.value
                .map(x => x.trim())
                .filter(x => x.length > 0)
        )
    }




// Mets la string finale dans panne
    panne.value.materiel_depose = deposeFinal.join(', ')
    const payload = {
        client_id: selectedClient.value.rowid,
        materiel_id: selectedMaterial.value.id, // ← LIEN CORRECT
        ...panne.value,
        services: selectedServices.value
    }

    try {
        const {data} = await axios.post('/api/prises-en-charge', payload)
        alert('Prise en charge enregistrée avec succès !')
        // Reset ou redirection possible ici
    } catch (err) {
        alert("Erreur lors de l'enregistrement : " + (err.response?.data?.message || 'inconnue'))
    }
}



const materielsExistants = ref([])



watch(selectedClient, async (client) => {
    if (client?.rowid) {
        const {data} = await axios.get(`/api/materiels/${client.rowid}`)
        materielsExistants.value = data
    }
}, {immediate: true})

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
    const {data} = await axios.get('/api/services')
    services.value = data
})


</script>
