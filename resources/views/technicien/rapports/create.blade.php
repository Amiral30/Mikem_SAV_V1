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
                
                <div class="form-group" style="margin-bottom: 35px;">
                    <label style="font-weight: 600; color: var(--text-primary); display: flex; align-items: center; gap: 8px; margin-bottom: 10px;">
                        <i class="las la-paperclip text-muted"></i> Pièces jointes (Photos, PDF, etc.)
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
    function formatBytes(bytes) {
        if (bytes === 0) return '0 Octet';
        const k = 1024;
        const sizes = ['Octets', 'Ko', 'Mo', 'Go'];
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        return parseFloat((bytes / Math.pow(k, i)).toFixed(1)) + ' ' + sizes[i];
    }

    // Utilisation de DataTransfer pour accumuler les fichiers sélectionnés
    const dt = new DataTransfer();

    // Stocke aussi les fichiers rejetés pour les afficher en rouge
    let rejectedFiles = [];

    function showFiles(input) {
        rejectedFiles = []; // Reset des erreurs visuelles pour cette sélection
        
        for (let file of input.files) {
            if (dt.items.length >= 10) {
                alert('⚠️ Limite de 10 fichiers autorisés atteinte.');
                break;
            }
            
            // Validation JS stricte pour empêcher le rechargement serveur (qui vide le form)
            if (file.size > 2 * 1024 * 1024) { // 2 Mo strict
                rejectedFiles.push({ file: file, reason: 'Poids supérieur à 2 Mo' });
                continue;
            }
            if (file.name.length > 80) { // Nom raisonnable
                rejectedFiles.push({ file: file, reason: 'Nom du fichier beaucoup trop long' });
                continue;
            }

            dt.items.add(file);
        }
        
        // Mettre à jour l'input pour la soumission du formulaire
        input.files = dt.files;
        
        renderFileList();
    }
    
    function removeFile(index) {
        const newDt = new DataTransfer();
        // Sauvegarder les descriptions actuelles avant re-rendu, en enlevant celle qu'on supprime
        const descriptions = Array.from(document.querySelectorAll('input[name="file_descriptions[]"]')).map(i => i.value);
        descriptions.splice(index, 1); 
        
        for (let i = 0; i < dt.items.length; i++) {
            if (i !== index) newDt.items.add(dt.files[i]);
        }
        
        dt.items.clear();
        for(let file of newDt.files) dt.items.add(file);
        document.getElementById('fileInput').files = dt.files;
        
        renderFileList(descriptions);
    }

    function renderFileList(preservedDescriptions = []) {
        const list = document.getElementById('fileList'); 
        
        // Si aucune description fournie (ex: ajout d'un fichier), on récupère celles en cours dans le DOM
        if (preservedDescriptions.length === 0 && document.querySelectorAll('input[name="file_descriptions[]"]').length > 0) {
           preservedDescriptions = Array.from(document.querySelectorAll('input[name="file_descriptions[]"]')).map(i => i.value);
        }
        
        list.innerHTML = '';
        
        Array.from(dt.files).forEach((f, index) => {
            const d = document.createElement('div'); 
            d.className='file-item'; 
            d.style.cssText = 'display:flex; flex-direction:column; gap: 10px; padding: 15px; background: var(--bg-input); border-radius: 8px; border-left: 4px solid var(--info); position: relative; margin-bottom: 5px;';
            
            // Logique de prévisualisation (Image vs autre fichier)
            let previewHTML = '';
            if (f.type.startsWith('image/')) {
                const url = URL.createObjectURL(f);
                previewHTML = `<img src="${url}" style="width: 45px; height: 45px; object-fit: cover; border-radius: 6px; border: 1px solid var(--border-color); margin-right: 15px;" alt="preview">`;
            } else {
                let iconClass = 'las la-file';
                if(f.type.includes('pdf')) iconClass = 'las la-file-pdf text-danger';
                previewHTML = `<i class="${iconClass}" style="font-size: 2.2rem; color: var(--info); margin-right: 15px; width: 45px; text-align: center;"></i>`;
            }

            // Récupérer la valeur de la description si elle avait été tapée
            const descValue = preservedDescriptions[index] || '';

            d.innerHTML = `
                <button type="button" onclick="removeFile(${index})" style="position: absolute; top: 12px; right: 12px; background: none; border: none; color: var(--danger); cursor: pointer; font-size: 1.4rem;" title="Retirer le fichier"><i class="las la-times-circle"></i></button>
                <div style="display: flex; align-items: center; justify-content: space-between; padding-right: 35px;">
                    <div style="display: flex; align-items: center; overflow: hidden;">
                        ${previewHTML}
                        <span style="font-weight: 500; font-size: 0.95rem; color: var(--text-primary); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 250px;" title="${f.name}">${f.name}</span>
                    </div>
                    <span class="text-muted" style="font-size:0.85rem; font-weight: bold; background: rgba(0,0,0,0.05); padding: 4px 8px; border-radius: 4px;">${formatBytes(f.size)}</span>
                </div>
                <!-- Titre / Description -->
                <input type="text" name="file_descriptions[]" class="form-control" value="${descValue}" placeholder="Description du fichier (ex: routeur défectueux)" style="margin-top: 5px; border-radius: 6px; padding: 10px; font-size: 0.9rem;">
            `; 
            list.appendChild(d);  
        });

        // Affiche les fichiers rejetés en rouge
        rejectedFiles.forEach(rej => {
            const d = document.createElement('div');
            d.style.cssText = 'display:flex; flex-direction:column; gap: 5px; padding: 15px; background: rgba(220, 53, 69, 0.05); border-radius: 8px; border: 2px dashed var(--danger); margin-bottom: 5px;';
            d.innerHTML = `
                <div style="display: flex; align-items: center; color: var(--danger); font-weight: bold;">
                    <i class="las la-exclamation-triangle" style="font-size: 1.6rem; margin-right: 10px;"></i>
                    Erreur : ${rej.file.name}
                </div>
                <div style="font-size: 0.85rem; color: var(--danger); opacity: 0.8; margin-left: 36px;">
                    Raison : ${rej.reason} — Fichier ignoré, il ne sera pas envoyé.
                </div>
            `;
            list.appendChild(d);
        });

        // Affiche ou masque le bouton + Ajouter
        const btnMore = document.getElementById('addMoreBtn');
        if (dt.items.length > 0 && dt.items.length < 10) {
            btnMore.style.display = 'block';
        } else {
            btnMore.style.display = 'none';
        }
    }

    // Effet Hover sur la zone de dépôt
    const dropArea = document.querySelector('.file-upload-area');
    dropArea.addEventListener('mouseover', () => { dropArea.style.borderColor = 'var(--accent-primary)'; dropArea.style.background = 'var(--bg-input)'; });
    dropArea.addEventListener('mouseout', () => { dropArea.style.borderColor = 'var(--border-color)'; dropArea.style.background = 'transparent'; });

    // Empêcher le double-clic à la soumission
    document.querySelector('form').addEventListener('submit', function(e) {
        const btn = this.querySelector('button[type="submit"]');
        if (btn.disabled) return e.preventDefault();
        btn.disabled = true;
        btn.innerHTML = '<i class="las la-spinner la-spin"></i> Envoi en cours...';
    });
</script>
@endsection
