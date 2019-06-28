import { Injectable } from "@angular/core";
import { Service }    from './Service';

@Injectable({
    providedIn: 'root'
})
export class UsersService extends Service {

    save(formValue: any) : Promise<any> {
        
        if (typeof formValue.image == 'object' && formValue.image) {
            
            const formData: FormData = new FormData();
            formData.append('image', formValue.image.file, formValue.image.file.name);
            formData.append('name', formValue.name);
            formData.append('email', formValue.email);
            formData.append('password', formValue.password);

            if (formValue.id) {
                formData.append('_method', 'PUT');
                return this.post(formData, `users/${formValue.id}`);
            } else
                return this.post(formData, 'users');

        } else
            return formValue.id ? this.put(formValue, `users/${formValue.id}`) : this.post(formValue, 'users');

    }

}