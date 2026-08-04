import { loginApi, logoutApi, meApi } from "../api/authApi";

export default class AuthService {

    static login(username: string, password: string) {
        return loginApi({
            username,
            password
        });
    }

    static me() {
        return meApi();
    }

    static logout() {
        return logoutApi();
    }

}