import axios, { AxiosError, AxiosResponse } from "axios";

const requestLimit = 10;
let requestCount = 0;
const resetInterval = 60000;

setInterval(() => {
    requestCount = 0;
}, resetInterval);

const axiosInstance = axios.create({
    baseURL: "",
    headers: {
        "X-Requested-With": "XMLHttpRequest",
        "Content-Type": "application/json",
        Accept: "application/json",
    },
});

axiosInstance.interceptors.request.use(
    function (config) {
        if (requestCount >= requestLimit) {
            return Promise.reject(new Error("Osiągnięto limit " + requestLimit + " żądań na minutę. Spróbuj ponownie za chwilę."));
        }

        requestCount++;
        return config;
    },
    function (error) {
        return Promise.reject(error);
    }
);

axiosInstance.interceptors.response.use(
    function (response: AxiosResponse) {
        return response;
    },
    function (error: AxiosError) {
        const { response } = error;

        if (response) {
            console.error(`Error: ${response.status} - ${response.data?.message}`);
        }

        return Promise.reject(error);
    }
);

export default axiosInstance;
