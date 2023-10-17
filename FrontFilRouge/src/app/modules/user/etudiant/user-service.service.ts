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
  
  store<U>(etudiants:any,classe_id:number): Observable<U> {
  const  body ={classe_id,etudiants}
    return this.http.post<U>(`${environment.apiUrl}/store`,  body);
  }
}
