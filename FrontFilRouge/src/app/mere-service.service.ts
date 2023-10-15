import { HttpClient } from '@angular/common/http';
import { Injectable } from '@angular/core';
import { Observable } from 'rxjs';
import { environment } from 'src/environnement/environment';

@Injectable({
  providedIn: 'root'
})
export abstract class MereServiceService<T> {


  constructor(private http:HttpClient) { }

  abstract getUri():string

  all<U>(): Observable<U> {
    const uri = this.getUri()
    return this.http.get<U>(`${environment.apiUrl}/${uri}`)
  }
  all1(page: number): Observable<any> {
    const uri = this.getUri();
    return this.http.get<any>(`${environment.apiUrl}/${uri}?page=${page}`);
  }


  store<U>(produit: T): Observable<U> {
    const uri = this.getUri()
    return this.http.post<U>(`${environment.apiUrl}/${uri}`,produit);
  }

  filtre<U>(id: number): Observable<U> {
    const uri = this.getUri()

    return this.http.get<U>(`${environment.apiUrl}/${uri}/filtre/${id}`)
  }
  filtre1<U>(id: number): Observable<U> {
    const uri = this.getUri()

    return this.http.get<U>(`${environment.apiUrl}/${uri}/filtre1/${id}`)
  }


  update<U>(article: T, id: number): Observable<U> {
    const uri = this.getUri()
    return this.http.put<U>(`${environment.apiUrl}/${uri}/${id}`, article)
  }

  delete<U>(id: number): Observable<U> {
    const uri = this.getUri()

    return this.http.delete<U>(`${environment.apiUrl}/${uri}/${id}`)
  }
  annnuler<U>(id: number): Observable<U> {
    const uri = this.getUri()

    return this.http.get<U>(`${environment.apiUrl}/${uri}/annuler/${id}`)
  }
  valider<U>(id: number): Observable<U> {
    const uri = this.getUri()

    return this.http.get<U>(`${environment.apiUrl}/${uri}/valider/${id}`)
  }
  invalider<U>(id: number): Observable<U> {
    const uri = this.getUri()

    return this.http.get<U>(`${environment.apiUrl}/${uri}/invalider/${id}`)
  }
}
