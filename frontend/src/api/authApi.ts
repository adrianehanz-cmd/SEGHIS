import axiosClient from "../infrastructure/axios/axiosClient";

export interface LoginRequest {
    username: string;
    password: string;
}

export const loginApi = async (payload: LoginRequest) => {
    const response = await axiosClient.post("/auth/login", payload);
    return response.data;
};

export const meApi = async () => {
    const response = await axiosClient.get("/auth/me");
    return response.data;
};

export const logoutApi = async () => {
    const response = await axiosClient.post("/auth/logout");
    return response.data;
};
export interface RegisterRequest {
    pid: string;
    login_id: string;
    password: string;
}

export const registerApi = async (payload: RegisterRequest) => {
    const response = await axiosClient.post("/auth/register", payload);
    return response.data;
};
