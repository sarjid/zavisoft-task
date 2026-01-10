import axios from "axios";
const axiosInstance = axios.create({
    baseURL: import.meta.env.VITE_API_URL,
});

axiosInstance.interceptors.request.use(
    function (config) {
        config.headers["Accept"] = "application/json";
        return config;
    },
    function (error) {
        return Promise.reject(error);
    }
);

export default axiosInstance;
