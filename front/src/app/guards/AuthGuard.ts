import { Injectable } from '@angular/core';
import { CanActivate, ActivatedRouteSnapshot, RouterStateSnapshot, Router } from '@angular/router';
import { Observable }  from 'rxjs';
import { AuthService } from 'app/services/AuthService';


@Injectable({
  providedIn: 'root'
})
export class AuthGuard implements CanActivate {

    constructor(private authService: AuthService,
                private router: Router){}

    canActivate(next: ActivatedRouteSnapshot,state: RouterStateSnapshot): Observable<boolean> | Promise<boolean> | boolean {
        
        if (this.authService.isAuthenticated())
            return true;
        
        this.router.navigate(['/']);
        return false;

    }

}