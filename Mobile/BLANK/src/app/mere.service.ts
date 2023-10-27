import { Injectable } from '@angular/core';
import { Observable, map } from 'rxjs';
import { HttpClient } from '@angular/common/http'; // Importez HttpClient
import { environment } from 'src/environments/environment';

@Injectable({
  providedIn: 'root'
})
export class MereService {

  constructor(private http: HttpClient) { }
  user: any
  isAuthenticated: boolean = false
  public isLoggedIn(): boolean {
    return this.isAuthenticated &&  localStorage.getItem("role") === "etudiant";
  }


  login(identifiant: any): Observable<any> {
    return this.http.post<{ token: string }>(environment.apiUrl + '/login', identifiant)
      .pipe(
        map(result => {
          localStorage.setItem('token', result.token);
          this.isAuthenticated = true;
          // console.log(result);
          this.user = result;
          localStorage.setItem('role', this.user.user.role);
          localStorage.setItem('photo', this.user.user.photo);
          localStorage.setItem('name', this.user.user.name);
          localStorage.setItem('id', this.user.user.id);
          return result;
        })
      );
  }

  logout() {
    let removeToken = localStorage.removeItem('token');
    this.isAuthenticated = false;

    if (removeToken == null) {
      // this.router.navigate(['auth']);
    }
  }

  coursEtu(id: any): Observable<any> {
    return this.http.get<any>(`${environment.apiUrl}/cours/coursEtu/${id}`);
  }
  // http://127.0.0.1:8000/api/session/sessionsEleve/6
  sessionsEleve(eleveId: any): Observable<any> {
    return this.http.get<any>(`${environment.apiUrl}/session/sessionsEleve/${eleveId}`);
  }
}
