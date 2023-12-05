import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { Observable } from 'rxjs';
import { environment } from 'src/environnement/environment';

@Injectable({
  providedIn: 'root'
})
export class UserServiceService {
  constructor(private http: HttpClient) { }

  all<U>(): Observable<U> {
    return this.http.get<U>(`${environment.apiUrl}/user`)
  }
  
  classeEleves<U>(id:number): Observable<U> {
    return this.http.get<U>(`${environment.apiUrl}/user/classeEleves/${id}`)
  }
  
  store<U>(etudiants:any): Observable<U> {
    const  body ={etudiants}
      return this.http.post<U>(`${environment.apiUrl}/store`,body);
    }

    storeClasse<U>(classe: any): Observable<U> {
  
      return this.http.post<U>(`${environment.apiUrl}/user/storeClasse`, classe);
    }
  
}
