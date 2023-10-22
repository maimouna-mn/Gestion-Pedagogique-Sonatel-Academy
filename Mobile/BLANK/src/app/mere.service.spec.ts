import { TestBed } from '@angular/core/testing';

import { MereService } from './mere.service';

describe('MereService', () => {
  let service: MereService;

  beforeEach(() => {
    TestBed.configureTestingModule({});
    service = TestBed.inject(MereService);
  });

  it('should be created', () => {
    expect(service).toBeTruthy();
  });
});
