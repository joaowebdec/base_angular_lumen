import { CommonModule }                   from '@angular/common';
import { UploadFileComponent }            from './upload-file.component';
import { NgModule }                       from '@angular/core';
import { MatIconModule, MatButtonModule } from '@angular/material';
import { ReactiveFormsModule }            from '@angular/forms';

@NgModule({
    declarations: [
        UploadFileComponent
    ],
    imports: [
        CommonModule,
        MatIconModule,
        MatButtonModule,
        ReactiveFormsModule
    ],
    exports: [
        UploadFileComponent
    ]
})
export class UploadFileModule { }
