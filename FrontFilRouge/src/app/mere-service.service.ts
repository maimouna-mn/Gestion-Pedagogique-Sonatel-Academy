import { HttpClient } from '@angular/common/http';
import { Injectable } from '@angular/core';
import { Observable } from 'rxjs';
import { environment } from 'src/environnement/environment';

@Injectable({
  providedIn: 'root'
})
export abstract class MereServiceService<T> {


  constructor(private http: HttpClient) { }

  abstract getUri(): string

  all<U>(): Observable<U> {
    const uri = this.getUri()
    return this.http.get<U>(`${environment.apiUrl}/${uri}`)
  }

  semestreAll<U>(): Observable<U> {
    const uri = this.getUri()
    return this.http.get<U>(`${environment.apiUrl}/${uri}/all`)
  }
  all1(page: number): Observable<any> {
    const uri = this.getUri();
    return this.http.get<any>(`${environment.apiUrl}/${uri}?page=${page}`);
  }


  store<U>(produit: T): Observable<U> {
    const uri = this.getUri()
    return this.http.post<U>(`${environment.apiUrl}/${uri}`, produit);
  }

  filtre<U>(id: number): Observable<U> {
    const uri = this.getUri()

    return this.http.get<U>(`${environment.apiUrl}/${uri}/filtre/${id}`)
  }
  profSessions<U>(professeurId: any): Observable<U> {
    const uri = this.getUri()

    return this.http.get<U>(`${environment.apiUrl}/${uri}/profSessions/${professeurId}`)
  }
  filtre1<U>(id: number): Observable<U> {
    const uri = this.getUri()

    return this.http.get<U>(`${environment.apiUrl}/${uri}/filtre1/${id}`)
  }
  coursprof(page: number, id: any): Observable<any> {
    const uri = this.getUri();
    return this.http.get<any>(`${environment.apiUrl}/${uri}/coursprof/${id}?page=${page}`);
  }

  // Route::get('/cours/filtreEtat/{etat}',[coursController::class,"filtreEtatCours"]);

  filtreEtatCours<U>(etat: number): Observable<U> {
    const uri = this.getUri()

    return this.http.get<U>(`${environment.apiUrl}/${uri}/filtreEtat/${etat}`)
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
  detailCours<U>(id: number): Observable<U> {
    const uri = this.getUri()
    return this.http.get<U>(`${environment.apiUrl}/${uri}/getCoursDetails/${id}`)
  }

  DemandeAnnulation<U>(session_cours_classe_id: number,motif:string): Observable<U> {
    const uri = this.getUri()
    return this.http.post<U>(`${environment.apiUrl}/${uri}/demandeAnnulation/${session_cours_classe_id}`,{motif})
  }
  listeDemandeAnnulation<U>(): Observable<U> {
    const uri = this.getUri()
    return this.http.get<U>(`${environment.apiUrl}/${uri}/demandesEnAttente`)
  }

  // SupprimerSession(Request $request, $session_cours_classe_id)
  SupprimerSession<U>(session_cours_classe_id: number): Observable<U> {
    const uri = this.getUri()
    return this.http.delete<U>(`${environment.apiUrl}/${uri}/supprimer/${session_cours_classe_id}`)
  }
}
