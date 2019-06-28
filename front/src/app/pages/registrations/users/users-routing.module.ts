import { NgModule } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';

/* Pages */
import { IndexComponent } from './index/index.component';
import { SaveComponent }  from './save/save.component';

const routes: Routes = [
    { path: '', component: IndexComponent },
    { path: 'save', component: SaveComponent },
    { path: ':id/save', component: SaveComponent}
];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule]
})
export class UsersRoutingModule { }
