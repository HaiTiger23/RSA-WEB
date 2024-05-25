var rsaAlgorithm = {
    name: "RSA-OAEP",
    modulusLength: 2048,
    publicExponent: new Uint8Array([0x01, 0x00, 0x01]),
    hash: { name: "SHA-256" }
};

var aesAlgorithm = {
    name: "AES-GCM",
    length: 256
};

var aesIVLength = 12;

/**
 * Hàm generateRsaKeys tạo ra cặp khóa RSA mới.
 * Nó trả về một promise được resolve với một đối tượng chứa cặp khóa công khai và riêng tư.
 * @return {Promise} Promise trả về cặp khóa RSA
 */
var generateRsaKeys = function () {
    // Tạo ra khóa RSA mới
    return crypto.subtle.generateKey(rsaAlgorithm,
        /* extractable: */ true, /* keyUsages: */ ["wrapKey", "unwrapKey"])
        .catch(function (error) { throw "Error generating keys."; })
        .then(function (rsaKey) {
            // Xuất khóa công khai
            var exportPublicKey = crypto.subtle.exportKey("spki", rsaKey.publicKey)
                .catch(function (error) { throw "Error exporting public key."; });
            // Xuất khóa riêng tư
            var exportPrivateKey = crypto.subtle.exportKey("pkcs8", rsaKey.privateKey)
                .catch(function (error) { throw "Error exporting private key."; });

            // Trả về một promise được resolve với cặp khóa
            return Promise.all([exportPublicKey, exportPrivateKey])
                .then(function (keys) { return { publicKeyBuffer: keys[0], privateKeyBuffer: keys[1] }; });
        });
};

/**
 * Hàm rsaEncrypt mã hóa dữ liệu bằng cách sử dụng khóa công khai RSA và khóa AES.
 * @param {ArrayBuffer} data - Dữ liệu cần mã hóa.
 * @param {ArrayBuffer} rsaPublicKeyBuffer - Dữ liệu khóa công khai RSA.
 * @return {Promise} Promise trả về dữ liệu đã được mã hóa.
 */
var rsaEncrypt = function (data, rsaPublicKeyBuffer) {
    // Nhập khóa công khai RSA từ dữ liệu buffer
    var importRsaPublicKey = crypto.subtle.importKey("spki", rsaPublicKeyBuffer, rsaAlgorithm,
        /* extractable: */ false, /* keyUsages: */ ["wrapKey"])
        // Xử lý lỗi nếu có
        .catch(function (error) { console.log(error); throw "Error importing public key."; });

    // Tạo ra khóa AES mới
    var generateAesKey = crypto.subtle.generateKey(aesAlgorithm,
        /* extractable: */ true, /* keyUsages: */ ["encrypt"])
        // Xử lý lỗi nếu có
        .catch(function (error) { throw "Error generating symmetric key."; });

    // Chờ cả hai promise được resolve
    return Promise.all([importRsaPublicKey, generateAesKey])
        .then(function (keys) {
            var rsaPublicKey = keys[0], aesKey = keys[1];
            var aesIV = crypto.getRandomValues(new Uint8Array(aesIVLength)); // Tạo ra IV
            var initializedAesAlgorithm = Object.assign({ iv: aesIV }, aesAlgorithm); // Khởi tạo thuộc tính IV của thuật toán AES

            // Gộp các bước mã hóa và gói khóa lại vào một promise
            var wrapAesKey = crypto.subtle.wrapKey("raw", aesKey, rsaPublicKey, rsaAlgorithm)
                // Xử lý lỗi nếu có
                .catch(function (error) { throw "Error encrypting symmetric key."; });
            var encryptData = crypto.subtle.encrypt(initializedAesAlgorithm, aesKey, data)
                // Xử lý lỗi nếu có
                .catch(function (error) { throw "Error encrypting data."; });

            // Chờ cả hai promise được resolve và gộp dữ liệu đã được mã hóa
            return Promise.all([wrapAesKey, encryptData])
                .then(function (buffers) {
                    var wrappedAesKey = new Uint8Array(buffers[0]), encryptedData = new Uint8Array(buffers[1]);
                    var encryptionState = new Uint8Array(wrappedAesKey.length + aesIV.length + encryptedData.length);
                    encryptionState.set(wrappedAesKey, 0);
                    encryptionState.set(aesIV, wrappedAesKey.length);
                    encryptionState.set(encryptedData, wrappedAesKey.length + aesIV.length);
                    return encryptionState.buffer;
                });
        });
};

var rsaDecrypt = function (data, rsaPrivateKeyBuffer) {
    return crypto.subtle.importKey("pkcs8", rsaPrivateKeyBuffer, rsaAlgorithm,
        /* extractable: */ false, /* keyUsages: */ ["unwrapKey"])
        .catch(function (error) { throw "Error importing private key."; })
        .then(function (rsaKey) {
            var wrappedAesKeyLength = rsaAlgorithm.modulusLength / 8;
            var wrappedAesKey = new Uint8Array(data.slice(0, wrappedAesKeyLength));
            var aesIV = new Uint8Array(data.slice(wrappedAesKeyLength, wrappedAesKeyLength + aesIVLength));
            var initializedaesAlgorithm = Object.assign({ iv: aesIV }, aesAlgorithm);

            return crypto.subtle.unwrapKey("raw", wrappedAesKey, rsaKey, rsaAlgorithm, initializedaesAlgorithm,
                /* extractable: */ false, /* keyUsages: */ ["decrypt"])
                .catch(function (error) { throw "Error decrypting symmetric key." })
                .then (function (aesKey) {
                    var encryptedData = new Uint8Array(data.slice(wrappedAesKeyLength + aesIVLength));

                    return crypto.subtle.decrypt(initializedaesAlgorithm, aesKey, encryptedData)
                        .catch(function (error) { throw "Error decrypting data." });
                });
        });
};
