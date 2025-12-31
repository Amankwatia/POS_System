<x-app-layout>
    @php
        $isAdmin = auth()->user()?->hasRole(\App\Models\Role::ADMIN);
        $productsRoute = $isAdmin ? route('admin.products.index') : route('manager.products.index');
        $importRoute = $isAdmin ? route('admin.products.import') : route('manager.products.import');
        $templateRoute = $isAdmin ? route('admin.products.import.template') : route('manager.products.import.template');
    @endphp

    <x-slot name="header">
        <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
            <div>
                <div class="flex items-center gap-2">
                    <span class="inline-flex items-center justify-center w-10 h-10 rounded-xl bg-gradient-to-br from-primary to-primary-600 text-white shadow-lg">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                        </svg>
                    </span>
                    <div>
                        <p class="text-xs font-medium uppercase tracking-wider text-primary">Bulk Import</p>
                        <h2 class="font-bold text-2xl text-gray-900">Import Products from CSV</h2>
                    </div>
                </div>
                <p class="mt-2 text-sm text-gray-500 max-w-xl">Upload a CSV file to add multiple products at once. Download the template to ensure your file has the correct format.</p>
            </div>
            <div class="flex flex-wrap gap-3">
                <a href="{{ $productsRoute }}" class="inline-flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 shadow-sm transition hover:bg-gray-50 hover:shadow">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Back to Products
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            {{-- Error Messages --}}
            @if (session('error'))
                <div class="flex items-start gap-3 rounded-xl bg-red-50 border border-red-200 p-4 shadow-sm">
                    <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-red-100 flex-shrink-0">
                        <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </span>
                    <div class="flex-1">
                        <p class="text-sm font-medium text-red-800">{{ session('error') }}</p>
                        @if(session('import_errors'))
                            <ul class="mt-2 text-sm text-red-700 list-disc list-inside space-y-1">
                                @foreach(session('import_errors') as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        @endif
                    </div>
                </div>
            @endif

            {{-- Success Messages --}}
            @if (session('status'))
                <div class="flex items-center gap-3 rounded-xl bg-emerald-50 border border-emerald-200 p-4 shadow-sm">
                    <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-emerald-100">
                        <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                    </span>
                    <p class="text-sm font-medium text-emerald-800">{{ session('status') }}</p>
                </div>
            @endif

            {{-- Instructions Card --}}
            <div class="bg-white shadow-sm ring-1 ring-gray-100 rounded-2xl overflow-hidden">
                <div class="bg-gradient-to-r from-primary to-primary-600 px-6 py-4">
                    <h3 class="text-lg font-semibold text-white">CSV File Requirements</h3>
                    <p class="text-sm text-primary-100">Ensure your file follows these guidelines</p>
                </div>
                <div class="p-6 space-y-4">
                    <div class="grid gap-4 sm:grid-cols-2">
                        <div class="flex items-start gap-3">
                            <span class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-primary-100 text-primary flex-shrink-0">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                            </span>
                            <div>
                                <p class="text-sm font-medium text-gray-900">File Format</p>
                                <p class="text-xs text-gray-500">CSV or TXT file, max 5MB</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-3">
                            <span class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-primary-100 text-primary flex-shrink-0">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"/>
                                </svg>
                            </span>
                            <div>
                                <p class="text-sm font-medium text-gray-900">Header Row</p>
                                <p class="text-xs text-gray-500">First row must contain column names</p>
                            </div>
                        </div>
                    </div>

                    <div class="border-t border-gray-100 pt-4">
                        <p class="text-sm font-medium text-gray-900 mb-3">Required Columns</p>
                        <div class="flex flex-wrap gap-2">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-red-100 text-red-700">name *</span>
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-red-100 text-red-700">sku *</span>
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-red-100 text-red-700">price *</span>
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-red-100 text-red-700">stock *</span>
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-700">description</span>
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-700">reorder_level</span>
                        </div>
                    </div>

                    <div class="border-t border-gray-100 pt-4">
                        <p class="text-sm font-medium text-gray-900 mb-2">Important Notes</p>
                        <ul class="text-sm text-gray-600 space-y-1.5">
                            <li class="flex items-start gap-2">
                                <svg class="w-4 h-4 text-primary mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                SKU must be unique - duplicate SKUs will be skipped
                            </li>
                            <li class="flex items-start gap-2">
                                <svg class="w-4 h-4 text-primary mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                Price should be a number (e.g., 15.99)
                            </li>
                            <li class="flex items-start gap-2">
                                <svg class="w-4 h-4 text-primary mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                Stock and reorder_level must be whole numbers
                            </li>
                            <li class="flex items-start gap-2">
                                <svg class="w-4 h-4 text-primary mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                If reorder_level is empty, it defaults to 5
                            </li>
                        </ul>
                    </div>

                    <div class="border-t border-gray-100 pt-4">
                        <a href="{{ $templateRoute }}" class="inline-flex items-center gap-2 text-sm font-medium text-primary hover:text-primary-800 transition">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                            </svg>
                            Download Sample Template
                        </a>
                    </div>
                </div>
            </div>

            {{-- Upload Form --}}
            <div class="bg-white shadow-sm ring-1 ring-gray-100 rounded-2xl overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100">
                    <h3 class="text-base font-semibold text-gray-900">Upload CSV File</h3>
                    <p class="text-sm text-gray-500">Select your prepared CSV file</p>
                </div>
                <form action="{{ $importRoute }}" method="POST" enctype="multipart/form-data" class="p-6">
                    @csrf
                    
                    <div class="space-y-4">
                        {{-- File Input --}}
                        <div 
                            x-data="{ 
                                fileName: '', 
                                isDragging: false,
                                handleFile(e) {
                                    const file = e.target.files[0];
                                    if (file) this.fileName = file.name;
                                },
                                handleDrop(e) {
                                    this.isDragging = false;
                                    const file = e.dataTransfer.files[0];
                                    if (file) {
                                        this.fileName = file.name;
                                        this.$refs.fileInput.files = e.dataTransfer.files;
                                    }
                                }
                            }"
                            @dragover.prevent="isDragging = true"
                            @dragleave.prevent="isDragging = false"
                            @drop.prevent="handleDrop($event)"
                            class="relative"
                        >
                            <label 
                                :class="isDragging ? 'border-primary bg-primary-50' : 'border-gray-300 hover:border-primary'"
                                class="flex flex-col items-center justify-center w-full h-48 border-2 border-dashed rounded-xl cursor-pointer bg-gray-50 transition-colors"
                            >
                                <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                    <svg class="w-10 h-10 mb-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                                    </svg>
                                    <p class="mb-2 text-sm text-gray-500">
                                        <span class="font-semibold">Click to upload</span> or drag and drop
                                    </p>
                                    <p class="text-xs text-gray-400">CSV or TXT file (max 5MB)</p>
                                    <p x-show="fileName" x-text="'Selected: ' + fileName" class="mt-2 text-sm font-medium text-primary"></p>
                                </div>
                                <input 
                                    type="file" 
                                    name="csv_file" 
                                    x-ref="fileInput"
                                    @change="handleFile($event)"
                                    accept=".csv,.txt"
                                    class="hidden" 
                                    required
                                />
                            </label>
                        </div>

                        @error('csv_file')
                            <p class="text-sm text-red-600">{{ $message }}</p>
                        @enderror

                        {{-- Submit Button --}}
                        <div class="flex items-center justify-end gap-3 pt-4">
                            <a href="{{ $productsRoute }}" class="px-4 py-2.5 text-sm font-medium text-gray-700 hover:text-gray-900 transition">
                                Cancel
                            </a>
                            <button 
                                type="submit" 
                                class="inline-flex items-center gap-2 px-6 py-2.5 bg-primary text-white text-sm font-semibold rounded-lg shadow-lg hover:bg-primary-800 focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2 transition"
                            >
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                                </svg>
                                Import Products
                            </button>
                        </div>
                    </div>
                </form>
            </div>

        </div>
    </div>
</x-app-layout>
