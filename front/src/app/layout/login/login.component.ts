import { Component, OnInit, ViewEncapsulation } from '@angular/core';
import { FormGroup, FormBuilder, Validators }   from '@angular/forms';

import { Router }         from '@angular/router';
import { fuseAnimations } from '@fuse/animations';

import { AuthService } from 'app/services/AuthService';

@Component({
    selector: 'login-page',
    templateUrl  : './login.component.html',
    styleUrls: ['./login.component.scss'],
    encapsulation: ViewEncapsulation.None,
    animations   : fuseAnimations
})
export class LoginComponent implements OnInit
{

    form: FormGroup;

    /**
     * Usado para exibir as mensagens de erro de login
     */
    msgError: any;

    /**
     * Usado para desativar o botão de login
     */
    loading: boolean;

    /**
     * Texto que exibe dentro do botão
     */
    texto: string = 'Login';

    constructor(private fb: FormBuilder,
                private router: Router,
                private authService: AuthService) {}

    ngOnInit(): void {

        this.form = this.fb.group({
            email: ['', [
                    Validators.required,
                    Validators.email
                ]
            ],
            password: ['', Validators.required]
        });
    }

    async login() {
        
        if (this.form.valid) {
            
            this.loading  = true;
            this.msgError = null;
            this.texto    = 'Entrando';

            this.authService.login(this.form.value).then(res => {
                this.router.navigate(['/dashboard']);
            }).catch(erro => {
                this.msgError = erro;
                this.loading  = false;
                this.texto    = 'Login';
            });

        }
            
    }
 
}
