import { Component, OnInit, Input, Output, EventEmitter } from '@angular/core';
import { MatSnackBar }                         from '@angular/material';

@Component({
    selector: 'upload-file',
    templateUrl  : './upload-file.component.html',
    styleUrls: ['./upload-file.component.scss']
})
export class UploadFileComponent implements OnInit
{

    @Input() type;
    @Output() file: EventEmitter<any> = new EventEmitter();
    @Input() previewImage: string;
    msgErro: string;

    fileTypesAllowed: any = {
        profile: ['image/png', 'image/jpeg']
    };

    previewInit: any = {
        profile: 'assets/images/avatars/profile.jpg'
    };

    img: any;
    fileName: string;

    constructor(private _matSnackBar: MatSnackBar) {}

    ngOnInit(): void {
        this.img = this.previewInit[this.type];
    }

    ngOnChanges() : void {

        if (this.previewImage)
            this.img = this.previewImage;
        
    }

    getFile(file: File) {
        
        if (this.fileTypesAllowed[this.type].indexOf(file.type) > -1) {
            
            if (this.type = 'profile') {

                /* Preview da imagem */
                let reader = new FileReader();
                reader.readAsDataURL(file); 
                reader.onload = (_event) => { 
                    this.img = reader.result; 
                };

            }

            this.fileName = file.name;

            this.file.emit({file});

        } else {

            let arrTypes     = this.fileTypesAllowed[this.type].map(item => item.split('/')[1]);
            let typesAllowed = arrTypes.join(', ');
            
            this._matSnackBar.open(`Somente arquivos com a extensão do tipo ${typesAllowed} são permitidos.`, 'Erro', {
                verticalPosition: 'top',
                duration        : 3000
            });
        }

    }

    removeFile() {
        this.img = this.previewInit[this.type];
        this.file.emit(null);
    }

}