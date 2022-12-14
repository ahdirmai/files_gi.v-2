<div>
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Shared</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></div>
                </div>
            </div>
            <div>
                <div class="form-group form-group-sm">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <div class="input-group-text">
                                <x-heroicon-s-magnifying-glass style="width:15px" />
                            </div>
                        </div>
                        <input type="search" class="form-control" wire:model="search">
                        {{-- <button class="btn btn-primary btn-sm btn-icon" data-toggle="modal"
                            data-target=".show-modal" data-title="Create Folder"
                            data-url="{{ route('folder.create') }}">
                            <x-heroicon-s-plus style="width:15px" />
                            <span>
                                Create
                            </span>
                        </button> --}}
                    </div>
                </div>

                <h2 class="section-title row">
                    Base Folder
                </h2>
                @include('shared._basefolder')
                {{ $sharedBaseFolder->links() }}

                <h2 class="section-title row">
                    Folder
                </h2>
                @include('shared.content._folder')
                {{ $sharedContentFolder->links() }}

                <h2 class="section-title row">
                    File
                </h2>
                @include('shared.content._file')
                {{ $sharedContentFile->links() }}

            </div>

        </section>
    </div>

    <x-modal.basic />

    <x-modal.detail />
</div>
