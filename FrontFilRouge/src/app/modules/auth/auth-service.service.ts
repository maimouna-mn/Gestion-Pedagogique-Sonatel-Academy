import { HttpClient } from '@angular/common/http';
import { Injectable } from '@angular/core';
import { Router } from '@angular/router';
import { Observable, map } from 'rxjs';
import { environment } from 'src/environnement/environment';

@Injectable({
  providedIn: 'root'
})
export abstract class AuthServiceService {

  abstract Uri(): string;

  constructor(private http: HttpClient,private router:Router) { }
  private isAuthenticated = false;
  user!:any
  public isLoggedIn(): boolean {
    return this.isAuthenticated;
  }
  
  isRp(): boolean {
    return  localStorage.getItem("role") === "responsable_rp";
  }
  isProf(): boolean {
    return localStorage.getItem("role") === "professeur";
  }
  isAttache(): boolean {
    return  localStorage.getItem("role") === "attache";
  }
  
  login(identifiant: any): Observable<any> {
    return this.http.post<{ token: string }>(environment.apiUrl + '/login', identifiant)
      .pipe(
        map(result => {
          localStorage.setItem('token', result.token);
          this.isAuthenticated = true;
          console.log(result);
          this.user=result
          localStorage.setItem('role', this.user.user.role);
          localStorage.setItem('id', this.user.user.id);
          return result;
        })
      );
  }

  logout() {

    let removeToken = localStorage.removeItem('token');
    this.isAuthenticated = false;

    if (removeToken == null) {
      this.router.navigate(['auth']);
    }
  }
}
