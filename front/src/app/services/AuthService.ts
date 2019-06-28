import { Injectable } from "@angular/core";
import { Service }    from './Service';

@Injectable({
    providedIn: 'root'
})
export class AuthService extends Service {

    /**
     * Tenta autenticar um usuário
     */
    login(params: any) : Promise<any> {

        return new Promise((resolve, reject) => {
            
            this.post(params, 'users/login').then(res => {

                if (res.code != 200)
                    reject(res.msg);
                else {
                    localStorage.setItem('token', res.token);
                    resolve(true);
                }
                
            });

        });

    }

    isAuthenticated() : boolean {
        return localStorage.getItem('token') ? true : false;
    }

}