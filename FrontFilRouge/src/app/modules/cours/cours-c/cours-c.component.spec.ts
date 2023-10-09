import { ComponentFixture, TestBed } from '@angular/core/testing';

import { CoursCComponent } from './cours-c.component';

describe('CoursCComponent', () => {
  let component: CoursCComponent;
  let fixture: ComponentFixture<CoursCComponent>;

  beforeEach(() => {
    TestBed.configureTestingModule({
      declarations: [CoursCComponent]
    });
    fixture = TestBed.createComponent(CoursCComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
