<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Inertia\Inertia;

class TakeitController extends Controller
{
    public function index()
    {
        $clients = DB::table('llx_societe')
            ->select('rowid', 'nom', 'code_client')
            ->orderBy('nom')
            ->get();

        return Inertia::render('Takeit', [
            'clients' => $clients
        ]);
    }

    public function search(Request $request)
    {
        $query = $request->get('q', '');
        $mots = explode(' ', $query);

        $results = DB::table('llx_societe')
            ->select('rowid', 'nom', 'address', 'zip', 'town', 'phone')
            ->when(count($mots), function ($q) use ($mots) {
                foreach ($mots as $mot) {
                    $q->where('nom', 'like', '%' . $mot . '%');
                }
            })
            ->orderBy('nom')
            ->limit(20)
            ->get();

        return response()->json($results);
    }

    public function createClient(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'address' => 'nullable|string',
            'zip' => 'nullable|string',
            'town' => 'nullable|string',
            'email' => 'nullable|email',
            'phone' => 'nullable|string',
        ]);

        $clientData = [
            "name" => $request->input('name'),
            "address" => $request->input('address'),
            "zip" => $request->input('zip'),
            "town" => $request->input('town'),
            "email" => $request->input('email'),
            "phone" => $request->input('phone'),
            "client" => "1",
            "code_client" => "-1"
        ];

        $response = Http::withHeaders([
            'DOLAPIKEY' => env('DOLIBARR_API_KEY'),
        ])->post(env('DOLIBARR_API_URL') . '/thirdparties', $clientData);


        if ($response->successful()) {
            return response()->json($response->json(), 201);
        } else {
            return response()->json([
                'message' => 'Erreur lors de la création du client',
                'status' => $response->status(),
                'body' => $response->body(),
            ], $response->status());
        }

    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'client_id' => 'required|integer',
            'materiel_id' => 'required|integer',
            'description' => 'nullable|string',
            'remarques' => 'nullable|string',
            'materiel_depose' => 'nullable|string',
            'demarrage_os' => 'nullable|boolean',
            'demarrage_pc' => 'nullable|boolean',
            'services' => 'nullable|array',
        ]);

        $priseId = DB::table('lara_prises_en_charge')->insertGetId([
            'client_id' => $data['client_id'],
            'materiel_id' => $data['materiel_id'],
            'description' => $data['description'] ?? null,
            'remarques' => $data['remarques'] ?? null,
            'materiel_depose' => $data['materiel_depose'] ?? null,
            'demarrage_os' => $data['demarrage_os'] ?? false,
            'demarrage_pc' => $data['demarrage_pc'] ?? false,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        if (!empty($data['services'])) {
            foreach ($data['services'] as $serviceId) {
                DB::table('lara_prises_en_charge_service')->insert([
                    'prise_en_charge_id' => $priseId,
                    'service_id' => $serviceId,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        return response()->json(['message' => 'Prise en charge enregistrée avec succès !'], 201);
    }

    public function storeMateriel(Request $request)
    {
        $data = $request->validate([
            'client_id' => 'required|integer',
            'type' => 'required|string',
            'marque' => 'nullable|string',
            'modele' => 'nullable|string',
            'numero_serie' => 'nullable|string',
            'accessoires' => 'nullable|string',
            'etat' => 'nullable|string',
            'mot_de_passe' => 'nullable|string',
        ]);

        $existing = DB::table('lara_materiels')
            ->where('client_id', $data['client_id'])
            ->where('type', $data['type'])
            ->where('marque', $data['marque'])
            ->where('modele', $data['modele'])
            ->where('numero_serie', $data['numero_serie'])
            ->first();

        if ($existing) {
            return response()->json($existing);
        }

        $id = DB::table('lara_materiels')->insertGetId([
            ...$data,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return response()->json(DB::table('lara_materiels')->find($id));
    }

    public function getMateriels($clientId)
    {
        return DB::table('lara_materiels')
            ->where('client_id', $clientId)
            ->orderByDesc('created_at')
            ->get();
    }

    public function dashboard()
    {
        $prises = DB::table('lara_prises_en_charge as p')
            ->leftJoin('llx_societe as c', 'p.client_id', '=', 'c.rowid')
            ->select('p.id', 'p.created_at', 'p.description', 'c.nom as client_nom')
            ->orderByDesc('p.created_at')
            ->get();

        return Inertia::render('Dashboard', [
            'prises' => $prises
        ]);
    }

    public function addComment(Request $request, $id)
    {
        $request->validate(['contenu' => 'required|string']);
        DB::table('lara_commentaires')->insert([
            'prise_en_charge_id' => $id,
            'contenu' => $request->contenu,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        return response()->json(['message' => 'Commentaire ajouté']);
    }

    public function updateStatut(Request $request, $id)
    {
        $request->validate(['statut' => 'required|in:en cours,en attente,terminé']);
        DB::table('lara_prises_en_charge')->where('id', $id)->update([
            'statut' => $request->statut,
            'updated_at' => now(),
        ]);
        return response()->json(['message' => 'Statut mis à jour']);
    }
}
