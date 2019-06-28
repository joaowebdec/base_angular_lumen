import { CommonModule }      from '@angular/common';
import { NgModule }          from '@angular/core';
import { NotFoundComponent } from './notfound/notfound.component';
import { SharedModule }      from 'app/shared.module';


@NgModule({
  declarations: [
    NotFoundComponent
  ],
  imports: [
    CommonModule,
    SharedModule
  ],
  providers: [],
})
export class ErrorsModule { }
