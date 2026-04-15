@extends('layouts.technicien')
@section('title', 'Mon Profil')
@section('page-title', 'Gestion du Profil')

@section('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css">
<style>
    .img-container img { max-width: 100%; }
    .cropper-view-box, .cropper-face { border-radius: 50%; }
    /* Style pour la modale custom */
    .modal-crop { position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.9); z-index: 10000; display: none; align-items: center; justify-content: center; padding: 20px; }
    .modal-crop-content { background: var(--card-bg); width: 100%; max-width: 500px; border-radius: 12px; overflow: hidden; }
    .modal-crop-header { padding: 15px 20px; border-bottom: 1px solid var(--border-color); display: flex; justify-content: space-between; align-items: center; }
    .modal-crop-body { padding: 20px; }
    .modal-crop-footer { padding: 15px 20px; border-top: 1px solid var(--border-color); display: flex; justify-content: flex-end; gap: 10px; }
</style>
@endsection

@section('content')
<div class="row">
    <div class="col-md-4">
        <div class="card" style="text-align: center; padding: 30px;">
            <div id="avatar-container" style="position: relative; width: 150px; height: 150px; margin: 0 auto 20px; border-radius: 50%; overflow: hidden; border: 4px solid var(--border-color); background: var(--bg-primary);">
                <label for="photo-upload" style="cursor: pointer; display: flex; width: 100%; height: 100%; align-items: center; justify-content: center; margin: 0;">
                    @if($user->profile_photo)
                        <img src="{{ asset('storage/' . $user->profile_photo) }}" id="avatar-preview" alt="Photo" 
                             style="width: 100%; height: 100%; object-fit: cover;">
                    @else
                        <div id="avatar-placeholder" style="font-size: 64px; color: var(--accent-primary); display: flex; align-items: center; justify-content: center;">
                            <i class="las la-user-astronaut"></i>
                        </div>
                    @endif
                    
                    <!-- Le bouton + flottant -->
                    <div style="position: absolute; bottom: 5px; right: 5px; background: var(--accent-primary); width: 40px; height: 40px; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; border: 3px solid var(--card-bg); box-shadow: 0 4px 10px rgba(0,0,0,0.2); z-index: 10;">
                        <i class="las la-plus"></i>
                    </div>
                </label>
            </div>
            
            <h3>{{ $user->name }}</h3>
            <p style="color: var(--text-muted); font-size: 0.8rem; margin-bottom: 0;">Saisissez vos infos et cliquez sur enregistrer</p>
        </div>
    </div>

    <div class="col-md-8">
        <div class="card">
            <div class="card-header"><h3><i class="las la-user-cog"></i> Informations personnelles</h3></div>
            <div class="card-body">
                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                @if($errors->any())
                    <div class="alert alert-danger">
                        <ul style="margin:0;">
                            @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('technicien.profile.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <!-- Inputs photo cachés -->
                    <input type="file" id="photo-upload" accept="image/*" style="display: none;" onchange="initCropper(this)">
                    <input type="hidden" name="photo_base64" id="photo-base64">

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Nom complet *</label>
                                <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Email *</label>
                                <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}" required>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Téléphone</label>
                        <input type="text" name="telephone" class="form-control" value="{{ old('telephone', $user->telephone) }}">
                    </div>

                    <hr style="margin: 30px 0; border-color: var(--border-color);">

                    <h4 style="margin-bottom: 20px;"><i class="las la-key"></i> Changer le mot de passe</h4>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Nouveau mot de passe</label>
                                <input type="password" name="password" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Confirmer le mot de passe</label>
                                <input type="password" name="password_confirmation" class="form-control">
                            </div>
                        </div>
                    </div>
                    <small style="color: var(--text-muted);">Laissez vide pour conserver le mot de passe actuel.</small>

                    <div class="mt-4">
                        <button type="submit" class="btn btn-primary btn-lg">Enregistrer les modifications</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modale de rognage -->
<div class="modal-crop" id="modalCrop">
    <div class="modal-crop-content">
        <div class="modal-crop-header">
            <h4 style="margin:0;"><i class="las la-crop-alt"></i> Cadrer votre photo</h4>
            <button type="button" onclick="closeModal()" style="background:none; border:none; color:var(--text-primary); font-size:1.5rem; cursor:pointer;">&times;</button>
        </div>
        <div class="modal-crop-body">
            <div class="img-container">
                <img id="image-to-crop" src="">
            </div>
        </div>
        <div class="modal-crop-footer">
            <button type="button" class="btn btn-secondary" onclick="closeModal()">Annuler</button>
            <button type="button" class="btn btn-primary" id="crop-button">Valider le rognage</button>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>
<script>
let cropper;

function initCropper(input) {
    const modal = document.getElementById('modalCrop');
    const image = document.getElementById('image-to-crop');
    const inputBase64 = document.getElementById('photo-base64');
    
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            image.src = e.target.result;
            modal.style.display = 'flex';
            
            if (cropper) cropper.destroy();
            
            cropper = new Cropper(image, {
                aspectRatio: 1,
                viewMode: 1,
                dragMode: 'move',
                autoCropArea: 1,
                restore: false,
                guides: false,
                center: false,
                highlight: false,
                cropBoxMovable: true,
                cropBoxResizable: true,
                toggleDragModeOnDblclick: false,
            });
        }
        reader.readAsDataURL(input.files[0]);
    }
}

document.getElementById('crop-button').addEventListener('click', function() {
    const inputBase64 = document.getElementById('photo-base64');
    const preview = document.getElementById('avatar-preview');
    const placeholder = document.getElementById('avatar-placeholder');
    
    const canvas = cropper.getCroppedCanvas({
        width: 400,
        height: 400,
    });
    
    const base64 = canvas.toDataURL('image/jpeg');
    inputBase64.value = base64;
    
    // Mise à jour du container
    const container = document.getElementById('avatar-container');
    container.style.borderColor = 'var(--accent-primary)';
    
    // Remplacement du contenu par l'image
    container.querySelector('label').innerHTML = `
        <img src="${base64}" id="avatar-preview" alt="Photo" 
             style="width: 100%; height: 100%; object-fit: cover; animation: fadeIn 0.5s ease;">
        <div style="position: absolute; bottom: 5px; right: 5px; background: var(--accent-primary); width: 40px; height: 40px; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; border: 3px solid var(--card-bg); box-shadow: 0 4px 10px rgba(0,0,0,0.2); z-index: 10;">
            <i class="las la-plus"></i>
        </div>
    `;

    if (!document.getElementById('preview-info')) {
        const info = document.createElement('div');
        info.id = 'preview-info';
        info.innerHTML = '<span class="badge" style="background: var(--accent-primary); color: white; display: inline-block; margin-top: 10px; font-size: 0.75rem;">Photo cadrée prête</span>';
        container.parentNode.appendChild(info);
    }
    
    closeModal();
});

function closeModal() {
    const modal = document.getElementById('modalCrop');
    modal.style.display = 'none';
    if (cropper) cropper.destroy();
}
</script>
<style>
@keyframes fadeIn {
    from { opacity: 0; transform: scale(0.9); }
    to { opacity: 1; transform: scale(1); }
}
#avatar-preview, #avatar-placeholder {
    transition: all 0.3s ease;
}
label[for="photo-upload"]:hover img, 
label[for="photo-upload"]:hover #avatar-placeholder {
    filter: brightness(0.8);
    transform: scale(1.02);
}
</style>
@endsection
