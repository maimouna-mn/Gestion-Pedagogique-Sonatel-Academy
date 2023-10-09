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

  // allSelect<U>(): Observable<U> {
  //   const uri = this.getUri()
  //   return this.http.get<U>(`${environment.apiUrl}/${uri}/all`)
  // }


  // index<U>():Observable<U>{
  //   const uri=this.getUri()
  //  return this.http.get<U>(`${environment.apiUrl}/${uri}`)
  // }

  // recherche<U>(code: any): Observable<U> {
  //   const uri = this.getUri()
  //   return this.http.get<U>(`${environment.apiUrl}/${uri}/recherche/${code}`);
  // }

  // store<U>(produit: T): Observable<U> {
  //   const uri = this.getUri()
  //   return this.http.post<U>(`${environment.apiUrl}/${uri}`,produit);
  // }
}
