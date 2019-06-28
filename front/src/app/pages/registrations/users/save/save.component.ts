import { Component, OnInit, ViewEncapsulation } from '@angular/core';
import { FormGroup, FormBuilder, Validators } from '@angular/forms';
import { Router, ActivatedRoute }             from '@angular/router';

/* Angular material */
import { MatSnackBar } from '@angular/material';

import { fuseAnimations } from '@fuse/animations';

/* Validators */
import { confirmPassword } from 'app/validators/confirmPassword';

/* Services */
import { UsersService }    from 'app/services/users.service';

/* Componente base para paginas de save */
import { SavePageComponent } from 'app/pages/save-page.component';


@Component({
    templateUrl: './save.component.html',
    encapsulation: ViewEncapsulation.None,
    animations   : fuseAnimations
})
export class SaveComponent extends SavePageComponent implements OnInit {

    /**
     * Path da imagem do usuário no servidor
     */
    userImage: string;

    constructor(private _fb: FormBuilder, 
                private _userService: UsersService,
                private _matSnackBar: MatSnackBar,
                private _route: ActivatedRoute,
                private _usersService: UsersService,
                private _router: Router) {

        super();

        this.form = this._fb.group({
            id: [''],
            name: ['', 
                [
                    Validators.required,
                    Validators.maxLength(50)
                ]
            ],
            email: ['', 
                [
                    Validators.required,
                    Validators.maxLength(80),
                    Validators.email
                ]
            ],
            password: ['',
                [
                    Validators.required,
                    Validators.maxLength(50)
                ]
            ],
            repeatPassword: ['',
                [
                    Validators.required,
                    Validators.maxLength(50),
                    confirmPassword('password')
                ]
            ],
            image: ['']
        });

    }

    async ngOnInit()  {
        const userId = this._route.snapshot.paramMap.get('id');
        
        if (userId) {
            this.update    = true;
            this.searching = true;

            let user = await this._userService.get('users/' + userId);
            user     = user.user;

            /* Seta os valores que iram ser alterados */
            this.form.get('id').setValue(user.id);
            this.form.get('name').setValue(user.name);
            this.form.get('email').setValue(user.email);
            this.form.get('image').setValue(user.image);

            if (user.image)
                this.userImage = this._usersService.getApi() + 'images/users/' + user.image;

            /* Remove validação de controls desnecessários */
            this.form.get('password').disable();
            this.form.get('repeatPassword').disable();
            
            this.searching = false;

        }

    }

    /**
     * Captura a imagem do componente
     */
    getImage(image: any) {
        this.form.get('image').setValue(image);
    }

    async save() {
        
        if (this.form.valid) {

            this.loading = true;

            /* Verifica se o email já não existe */
            let result = await this._userService.get('users/exists/email/' + btoa(this.form.value.email));

            /* Tenta cadastrar */
            if (result.code == 200 || this.update) {
                result = await this._userService.save(this.form.value);

                if (result.code == 200)
                    this._router.navigate(['/registrations/users']);
            }

            this._matSnackBar.open(result.msg, result.code == 200 ? 'Sucesso' : 'Erro', {
                verticalPosition: 'top',
                duration        : 2000
            });

            this.loading = false;

        } else {
            Object.keys(this.form.value).forEach(item => {
                this.form.get(item).markAsTouched();
            });
        }

    }

}