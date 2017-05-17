$('#insertstates').validate({
  rules: {
    nazev: {
      required: true,
      maxlength: 20
    },
    kontakt: {
//      required: true,
      maxlength: 20
    },
    email: {
      email:true
    },
    telefon: {
      maxlength: 20
    },
    poznamka: {
      maxlength: 50
    },
    states: {
      required: true,
    },
  },
  messages: {
    nazev: {
      required:'Vložte prosím název klienta',
      maxlength:'Zkraťte název na max. 20 znaků'
    },
    kontakt: {
      maxlength:'Zkraťte jméno na max. 20 znaků'
    },
    email: 'Zadejte platný email',
    telefon: 'Maximální délka je 20 znaků',
    poznamka: 'Maximální délka je 50 znaků',
    states: 'Vyberte stav klienta'
  }
});
