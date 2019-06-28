import { Component, Inject }                  from '@angular/core';
import { FormBuilder, FormGroup, Validators } from '@angular/forms';
import { MAT_DIALOG_DATA, MatSnackBar, MatDialogRef }       from '@angular/material';

/* Meus validators */
import { confirmPassword } from 'app/validators/confirmPassword';

/* Meus services */
import { UsersService } from 'app/services/users.service';

@Component({
    templateUrl  : './user-password.component.html'
})
export class UserPasswordComponent
{

    /**
     * Formulário
     */
    form: FormGroup;

    /**
     * Flag para exibir o loading
     */
    loading: boolean = false;

    constructor(private _fb: FormBuilder,
                @Inject(MAT_DIALOG_DATA) public dados: any,
                private _userService: UsersService,
                private _matSnackBar: MatSnackBar,
                private _dialogRef: MatDialogRef<UserPasswordComponent>) {
        
        this.form = this._fb.group({
            id: [dados.id],
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
            ]
        });

    }

    /* Altera a senha do usuário */
    async changePassword() {
        
        if (this.form.valid) {

            this.loading = true;

            const result = await this._userService.put(this.form.value, `users/${this.form.value.id}/password`);
            this._matSnackBar.open(result.msg, result.code == 200 ? 'Sucesso' : 'Erro', {
                verticalPosition: 'top',
                duration        : 2000
            });
            
            /* Se auto fecha */
            this._dialogRef.close();

        } else
            Object.keys(this.form.value).forEach(item => this.form.get(item).markAsTouched());

    }

}
