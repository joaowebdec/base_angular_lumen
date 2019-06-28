import { NgModule }         from '@angular/core';
import { CommonModule }     from '@angular/common';
import { HttpClientModule } from '@angular/common/http';

import { 
            MatSidenavModule, 
            MatFormFieldModule, 
            MatIconModule, 
            MatCheckboxModule, 
            MatInputModule, 
            MatButtonModule,
            MatProgressSpinnerModule, 
            MatTableModule,
            MatRippleModule,
            MatMenuModule,
            MatDialogModule,
            MatSnackBarModule,
            MatRadioModule,
            MatDatepickerModule,
            MAT_DATE_FORMATS,
            MatProgressBarModule
        } from '@angular/material';
        
import { FuseSharedModule }        from '@fuse/shared.module';
import { RouterModule }            from '@angular/router';
import { ReactiveFormsModule }     from '@angular/forms';

/* Moment */
import { MatMomentDateModule } from '@angular/material-moment-adapter';

/* Meus modulos */
import { HeaderModule } from './components/header/header.module';
import { LoadingPulseModule } from './components/loading-pulse/loading-pulse.module';

export const MY_FORMATS = {
    parse: {
        dateInput: 'DD/MM/YYYY',
    },
    display: {
        dateInput: 'DD/MM/YYYY',
        monthYearLabel: 'MMM YYYY',
        dateA11yLabel: 'LL',
        monthYearA11yLabel: 'MMMM YYYY',
    },
};

@NgModule({
    exports: [
        MatSidenavModule,
        MatButtonModule,
        FuseSharedModule,
        RouterModule,
        MatFormFieldModule,
        MatIconModule,
        MatCheckboxModule,
        ReactiveFormsModule,
        MatInputModule,
        MatProgressSpinnerModule,
        CommonModule,
        HttpClientModule,
        MatMomentDateModule,
        MatTableModule,
        MatRippleModule,
        MatMenuModule,
        MatDialogModule,
        MatSnackBarModule,
        HeaderModule,
        MatRadioModule,
        MatDatepickerModule,
        MatProgressBarModule,
        LoadingPulseModule
    ],
    providers: [
        { provide: MAT_DATE_FORMATS, useValue: MY_FORMATS }
    ]
})
export class SharedModule {}