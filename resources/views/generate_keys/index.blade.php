<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between">
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                {{ __('Khóa RSA') }}
            </h2>

        </div>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="mb-4 overflow-hidden bg-white shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h2 class="text-xl font-semibold leading-tight text-gray-800">
                        {{ __('Sinh khóa RSA') }}
                    </h2>
                    <div class="flex justify-end">
                        <x-primary-button id="generate_button">{{ __('Sinh khóa mới') }}</x-primary-button>
                    </div>
                    <form method="post" action="{{ route('generate-key.store') }}" class="mt-6 hidden space-y-6"
                        id="generateForm">
                        @csrf
                        <div>
                            <x-input-label for="name" :value="__('Tên khóa')" />
                            <x-text-input id="name" class="mt-1 block w-full" type="text" name="name_key"
                                autofocus />
                        </div>
                        <div>
                            <div class="flex justify-between">
                                <x-input-label for="name" :value="__('Khóa Public')" />
                                <a id="public-key-download" class="download"
                                    download="public_key_rsa.rsa"><x-secondary-button>Tải xuống</x-secondary-button></a>
                            </div>
                            <textarea id="public-key-text" rows="10" name="public_key" type="text" class="mt-1 block w-full" required
                                readonly></textarea>

                        </div>
                        <div>
                            <div class="flex justify-between">
                                <x-input-label for="name" :value="__('Khóa Private')" />
                                <a id="private-key-download" class="download"
                                    download="private_key_rsa.rsa"><x-secondary-button>Tải
                                        xuống</x-secondary-button></a>
                            </div>
                            <textarea id="private-key-text" rows="10" name="private_key" type="text" class="mt-1 block w-full" required
                                readonly></textarea>

                        </div>
                        <div class="flex items-center gap-4">
                            <x-primary-button>{{ __('Lưu lại') }}</x-primary-button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="mb-4 overflow-hidden bg-white shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h2 class="text-xl font-semibold leading-tight text-gray-800">
                        {{ __('Danh sách khóa RSA') }}
                    </h2>
                </div>
                <div>

                    <table id="example" class="display" style="width:100%">
                        <thead>
                            <tr>
                                <th>Mã</th>
                                <th>Tên mã</th>
                                <th>Ngày tạo</th>
                                <th>Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($list_keys as $key)
                                <tr>
                                    <td>{{ $key->id }}</td>
                                    <td>{{ $key->name }}</td>
                                    <td>{{ $key->created_at }}</td>
                                    <td class="flex space-x-2 justify-end">
                                        <form action="{{ route('generate-key.destroy', $key->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <x-danger-button type="submit">Xóa</x-danger-button>
                                        </form>
                                        <a class="download_public_key" href="{{ route('generate-key.down_public', $key->id) }}"><x-secondary-button>Tải public key</x-secondary-button></a>
                                        <a  class="download_private_key"  href="{{ route('generate-key.down_private', $key->id) }}"><x-secondary-button>Tải private key</x-secondary-button></a>

                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <x-slot name="script">


        <script>
            new DataTable('#example', {
                "order": [
                    [0, "desc"]
                ]
            });
            var generateForm = document.getElementById("generateForm");
            var publicKeyText = document.getElementById("public-key-text");
            var publicKeyDownload = document.getElementById("public-key-download");
            var privateKeyText = document.getElementById("private-key-text");
            var privateKeyDownload = document.getElementById("private-key-download");
            var generate_button = document.getElementById("generate_button");

            var success = function(keys) {
                publicKeyText.value = arrayBufferToPem(keys.publicKeyBuffer, "RSA PUBLIC KEY");
                publicKeyDownload.href = window.URL.createObjectURL(
                    new Blob([publicKeyText.value], {
                        type: "application/octet-stream"
                    }));
                privateKeyText.value = arrayBufferToPem(keys.privateKeyBuffer, "RSA PRIVATE KEY");
                privateKeyDownload.href = window.URL.createObjectURL(
                    new Blob([privateKeyText.value], {
                        type: "application/octet-stream"
                    }));
                generateForm.style.display = "block";

            };
            var error = function(error) {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: error,
                })
            };

            generate_button.addEventListener("click", function() {
                generateRsaKeys().then(success, error);
            });
        </script>

    </x-slot>
</x-app-layout>
