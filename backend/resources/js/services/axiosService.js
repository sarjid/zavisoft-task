import axios from "axios";
import { useAuth, useToken } from "@/stores";

const axiosInstance = axios.create({
    baseURL: import.meta.env.VITE_APP_URL + "/api",
});

axiosInstance.interceptors.request.use(
    function (config) {
        const tokenStore = useToken();
        const auth = useAuth();
        if (auth?.getAuthStatus) {
            config.headers["Authorization"] = `Bearer ${tokenStore.getToken}`;
        }
        config.headers["Accept"] = "application/json";
        return config;
    },
    function (error) {
        return Promise.reject(error);
    }
);

axiosInstance.interceptors.response.use(
    (response) => {
        return response;
    },
    (error) => {
        if (error.response && error.response.status === 401) {
            const auth = useAuth();
            auth.removeAuthInfo();
        }
        return Promise.reject(error);
    }
);

export default axiosInstance;
