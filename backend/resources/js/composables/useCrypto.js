
import CryptoJS from "crypto-js";
const SECRET_KEY = "HABILCPOP";

export const useCrypto = () => {

    function encrypt(value) {
        return CryptoJS.AES.encrypt(value, SECRET_KEY).toString();
    }

    function decrypt(cipherText) {
        try {
            const bytes = CryptoJS.AES.decrypt(cipherText, SECRET_KEY);
            return bytes.toString(CryptoJS.enc.Utf8);
        } catch (e) {
            return null;
        }
    }

    return {
        encrypt,
        decrypt
    }
}
