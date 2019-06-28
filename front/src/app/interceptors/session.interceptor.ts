import { HttpEvent, HttpInterceptor, HttpHandler, HttpRequest } from '@angular/common/http';
import { Observable } from 'rxjs';
import { AuthService } from 'app/services/AuthService';
  
export class SessionInterceptor implements HttpInterceptor {

    // constructor(private authService: AuthService) {}

    intercept(req: HttpRequest<any>, next: HttpHandler): Observable<HttpEvent<any>> {

        if (localStorage.getItem('token')) {
            const clonedRequest = req.clone({ headers: req.headers.set('Authorization', localStorage.getItem('token')) });
            return next.handle(clonedRequest);
        } else
            return next.handle(req);
        
    }

}