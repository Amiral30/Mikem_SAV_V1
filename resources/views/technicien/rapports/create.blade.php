@extends('layouts.technicien')
@section('title', 'Rapport MIKEM')
@section('page-title', 'Rédiger un Rapport')
@section('page-subtitle', $mission->titre)

@section('content')
<div style="max-width:850px; margin: 0 auto;">
    <div class="card shadow-sm" style="border: none; border-radius: 12px; overflow: hidden;">
        <div class="card-header" style="background: var(--bg-secondary); border-bottom: 1px solid var(--border-color); padding: 25px 30px;">
            <h3 style="margin: 0; font-size: 1.4rem; color: var(--text-primary); display: flex; align-items: center; gap: 10px;">
                <i class="las la-file-signature" style="font-size: 1.8rem; color: var(--accent-primary);"></i> 
                Rapport d'Intervention
            </h3>
        </div>
        
        <div class="card-body" style="padding: 30px;">
            @if($errors->any())
                <div class="alert alert-danger" style="border-radius: 8px;">
                    <ul style="margin:0;padding-left:20px;">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
                </div>
            @endif
            
            <!-- Récapitulatif de la mission -->
            <div style="background: var(--bg-input); padding: 20px; border-radius: 8px; margin-bottom: 30px; border-left: 4px solid var(--accent-primary);">
                <div style="font-size: 1.1rem; font-weight: bold; color: var(--text-primary); margin-bottom: 8px;">{{ $mission->titre }}</div>
                <div style="display: flex; gap: 15px; font-size: 0.9rem; color: var(--text-secondary); flex-wrap: wrap;">
                    <span><i class="las la-tag"></i> {{ $mission->type_mission }}</span>
                    <span><i class="las la-calendar"></i> {{ $mission->date_mission->format('d/m/Y') }}</span>
                    <span><i class="las la-map-marker"></i> {{ $mission->adresse }}</span>
                </div>
            </div>

            <form action="{{ route('technicien.rapports.store', $mission) }}" method="POST" enctype="multipart/form-data">
                @csrf
                
                <div class="form-group" style="margin-bottom: 25px;">
                    <label for="deroulement" style="font-weight: 600; color: var(--text-primary); display: flex; align-items: center; gap: 8px; margin-bottom: 10px;">
                        <i class="las la-align-left text-muted"></i> Déroulement de la mission <span class="text-danger">*</span>
                    </label>
                    <textarea id="deroulement" name="deroulement" class="form-control" rows="5" placeholder="Décrivez en détail comment s'est déroulée l'intervention..." style="resize: vertical; padding: 15px; border-radius: 8px;" required>{{ old('deroulement') }}</textarea>
                </div>
                
                <div class="form-group" style="margin-bottom: 25px;">
                    <label for="actions_realisees" style="font-weight: 600; color: var(--text-primary); display: flex; align-items: center; gap: 8px; margin-bottom: 10px;">
                        <i class="las la-tools text-muted"></i> Actions réalisées / Solutions apportées <span class="text-danger">*</span>
                    </label>
                    <textarea id="actions_realisees" name="actions_realisees" class="form-control" rows="5" placeholder="Listez les tâches effectuées, le matériel installé ou réparé..." style="resize: vertical; padding: 15px; border-radius: 8px;" required>{{ old('actions_realisees') }}</textarea>
                </div>
                
                <div class="form-group" style="margin-bottom: 25px;">
                    <label for="difficultes" style="font-weight: 600; color: var(--text-primary); display: flex; align-items: center; gap: 8px; margin-bottom: 10px;">
                        <i class="las la-exclamation-triangle text-muted"></i> Difficultés rencontrées (Optionnel)
                    </label>
                    <textarea id="difficultes" name="difficultes" class="form-control" rows="3" placeholder="Y a-t-il eu des imprévus, des pièces manquantes, un accès difficile ?" style="resize: vertical; padding: 15px; border-radius: 8px;">{{ old('difficultes') }}</textarea>
                </div>
                
                {{-- ===== FICHE DE PASSAGE SIGNÉE (OBLIGATOIRE) ===== --}}
                <div class="form-group" style="margin-bottom: 30px; border: 2px solid var(--accent-primary); border-radius: 12px; padding: 20px; background: rgba(var(--accent-primary-rgb, 15,52,96), 0.04);">
                    <label style="font-weight: 700; color: var(--accent-primary); display: flex; align-items: center; gap: 10px; margin-bottom: 12px; font-size: 1.05rem;">
                        <i class="las la-file-signature" style="font-size: 1.5rem;"></i>
                        Fiche de Passage Signée par le Client <span class="text-danger">*</span>
                    </label>
                    <p class="text-muted" style="font-size: 0.85rem; margin-bottom: 15px;">
                        <i class="las la-info-circle"></i> Ce document est <strong>obligatoire</strong> pour valider votre rapport. Prenez une photo nette de la fiche signée par le client.
                    </p>
                    <div class="fiche-upload-area" onclick="document.getElementById('ficheInput').click()" style="border: 2px dashed var(--accent-primary); text-align: center; padding: 25px 20px; border-radius: 8px; cursor: pointer; transition: all 0.2s;">
                        <div><i class="las la-camera" style="font-size: 3rem; color: var(--accent-primary);"></i></div>
                        <p style="margin: 8px 0 4px; font-weight: bold; color: var(--text-primary);">Cliquez pour photographier / charger la fiche</p>
                        <p class="text-muted" style="font-size: 0.8rem; margin: 0;">JPG, PNG ou PDF — 2 Mo max</p>
                    </div>
                    <input type="file" id="ficheInput" name="fiche_passage" accept=".jpg,.jpeg,.png,.pdf" style="display:none;" onchange="showFiche(this)" required>
                    <div id="fichePreview" style="margin-top: 12px;"></div>
                </div>

                {{-- ===== PIÈCES JOINTES OPTIONNELLES ===== --}}
                <div class="form-group" style="margin-bottom: 35px;">
                    <label style="font-weight: 600; color: var(--text-primary); display: flex; align-items: center; gap: 8px; margin-bottom: 10px;">
                        <i class="las la-paperclip text-muted"></i> Autres pièces jointes (Photos, PDF, etc.)
                    </label>
                    <div class="file-upload-area" onclick="document.getElementById('fileInput').click()" style="border: 2px dashed var(--border-color); text-align: center; padding: 30px 20px; border-radius: 8px; cursor: pointer; transition: all 0.2s;">
                        <div class="upload-icon"><i class="las la-cloud-upload-alt" style="font-size: 3.5rem; color: var(--accent-primary);"></i></div>
                        <p style="margin: 10px 0 5px; font-weight: bold; color: var(--text-primary);">Cliquez ici pour déposer vos fichiers</p>
                        <p class="text-muted" style="font-size: 0.85rem; margin: 0;">(Formats supportés: JPG, PNG, PDF, etc. — Max 10 fichiers de 2 Mo max chacun)</p>
                    </div>
                    <input type="file" id="fileInput" name="fichiers[]" multiple accept=".jpg,.jpeg,.png,.gif,.pdf,.doc,.docx,.xls,.xlsx" style="display:none;" onchange="showFiles(this)">
                    <div id="fileList" class="file-list mt-3" style="display: flex; flex-direction: column; gap: 10px;"></div>
                    <button type="button" id="addMoreBtn" class="btn btn-outline-primary mt-2" onclick="document.getElementById('fileInput').click()" style="display: none; padding: 12px; width: 100%; background: rgba(0,0,0,0.02); border: 2px dashed var(--accent-primary); border-radius: 8px; color: var(--accent-primary); font-weight: bold; cursor: pointer; transition: 0.2s;"><i class="las la-plus-circle" style="font-size:1.3rem;"></i> Cliquer pour ajouter un autre fichier</button>
                </div>

                <hr style="border: none; border-top: 1px solid var(--border-color); margin-bottom: 25px;">

                <div style="display: flex; justify-content: flex-end; gap: 15px;">
                    <a href="{{ route('technicien.missions.show', $mission) }}" class="btn btn-secondary btn-lg" style="padding: 12px 25px; border-radius: 8px;">Annuler</a>
                    <button type="submit" class="btn btn-primary btn-lg" style="padding: 12px 35px; border-radius: 8px; font-weight: bold;"><i class="las la-paper-plane" style="margin-right: 8px;"></i> Soumettre le Rapport</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // === Fiche de passage ===
    function showFiche(input) {
        const preview = document.getElementById('fichePreview');
        const file = input.files[0];
        if (!file) { preview.innerHTML = ''; return; }
        if (file.type.startsWith('image/')) {
            const url = URL.createObjectURL(file);
            preview.innerHTML = `<div style="display:flex;align-items:center;gap:12px;padding:12px;background:var(--bg-input);border-radius:8px;border:1px solid var(--accent-primary);">
                <img src="${url}" style="width:60px;height:60px;object-fit:cover;border-radius:6px;">
                <div><div style="font-weight:600;color:var(--text-primary)">${file.name}</div><div class="text-muted" style="font-size:0.8rem">✅ Fiche chargée</div></div>
            </div>`;
        } else {
            preview.innerHTML = `<div style="padding:12px;background:var(--bg-input);border-radius:8px;border:1px solid var(--accent-primary);">
                <i class="las la-file-pdf" style="color:red;font-size:1.5rem;"></i> <strong>${file.name}</strong> — ✅ Fiche PDF chargée
            </div>`;
        }
    }

    // === Pièces jointes ===
    function formatBytes(bytes) {
        if (bytes === 0) return '0 Octet';
        const k = 1024;
        const sizes = ['Octets', 'Ko', 'Mo', 'Go'];
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        return parseFloat((bytes / Math.pow(k, i)).toFixed(1)) + ' ' + sizes[i];
    }

    const dt = new DataTransfer();
    let rejectedFiles = [];

    function showFiles(input) {
        rejectedFiles = [];
        for (let file of input.files) {
            if (dt.items.length >= 10) { alert('⚠️ Limite de 10 fichiers atteinte.'); break; }
            if (file.size > 2 * 1024 * 1024) { rejectedFiles.push({ file, reason: 'Poids supérieur à 2 Mo' }); continue; }
            dt.items.add(file);
        }
        input.files = dt.files;
        renderFileList();
    }

    function removeFile(index) {
        const newDt = new DataTransfer();
        for (let i = 0; i < dt.items.length; i++) { if (i !== index) newDt.items.add(dt.files[i]); }
        dt.items.clear();
        for (let file of newDt.files) dt.items.add(file);
        document.getElementById('fileInput').files = dt.files;
        renderFileList();
    }

    function renderFileList() {
        const list = document.getElementById('fileList');
        list.innerHTML = '';
        Array.from(dt.files).forEach((f, index) => {
            const d = document.createElement('div');
            d.style.cssText = 'display:flex;flex-direction:column;gap:8px;padding:15px;background:var(--bg-input);border-radius:8px;border-left:4px solid var(--accent-primary);position:relative;';
            let icon = f.type.startsWith('image/') ? `<img src="${URL.createObjectURL(f)}" style="width:40px;height:40px;object-fit:cover;border-radius:4px;">` : `<i class="las la-file-pdf" style="font-size:2rem;color:red;"></i>`;
            d.innerHTML = `
                <button type="button" onclick="removeFile(${index})" style="position:absolute;top:10px;right:10px;background:none;border:none;color:var(--danger);cursor:pointer;font-size:1.3rem;"><i class="las la-times-circle"></i></button>
                <div style="display:flex;align-items:center;gap:12px;padding-right:30px;">
                    ${icon}
                    <span style="flex:1;font-size:0.9rem;font-weight:500;">${f.name} <span class="text-muted">(${formatBytes(f.size)})</span></span>
                </div>
                <input type="text" name="file_descriptions[]" class="form-control" placeholder="Description du fichier (ex: routeur défectueux)" style="border-radius:6px;padding:10px;font-size:0.9rem;">
            `;
            list.appendChild(d);
        });
        rejectedFiles.forEach(rej => {
            const d = document.createElement('div');
            d.style.cssText = 'padding:10px;background:rgba(220,53,69,0.05);border-radius:8px;border:1px dashed red;color:red;font-size:0.85rem;';
            d.innerHTML = `<i class="las la-exclamation-triangle"></i> <strong>${rej.file.name}</strong> — ${rej.reason}`;
            list.appendChild(d);
        });
        document.getElementById('addMoreBtn').style.display = (dt.items.length > 0 && dt.items.length < 10) ? 'block' : 'none';
    }

    document.querySelector('form').addEventListener('submit', function(e) {
        const btn = this.querySelector('button[type="submit"]');
        if (btn.disabled) return e.preventDefault();
        btn.disabled = true;
        btn.innerHTML = '<i class="las la-spinner la-spin"></i> Envoi en cours...';
    });
</script>
@endsection
