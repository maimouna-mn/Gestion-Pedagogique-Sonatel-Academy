import { NgModule } from '@angular/core';
import { RouterModule, Routes } from '@angular/router';
import { AuthGuard } from './modules/auth/auth.guard';

const routes: Routes = [
  { path: '', redirectTo: 'auth', pathMatch: 'full' },
  // { path: 'cours', loadChildren: () => import('./modules/cours/cours.module').then(m => m.CoursModule) }, 
  { path: 'cours', loadChildren: () => import('./modules/cours/cours.module').then(m => m.CoursModule) ,canActivate: [AuthGuard]}, 
  { path: 'auth', loadChildren: () => import('./modules/auth/auth.module').then(m => m.AuthModule) },
  { path: 'user', loadChildren: () => import('./modules/user/user.module').then(m => m.UserModule) }];

@NgModule({
  imports: [RouterModule.forRoot(routes)],
  exports: [RouterModule]
})
export class AppRoutingModule { }
