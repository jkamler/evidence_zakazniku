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
      maxlength:'Maximální délka je 20 znaků'
    },
    kontakt: {
      maxlength:'Maximální délka je 20 znaků'
    },
    email: 'Zadejte platný email',
    telefon: 'Maximální délka je 20 znaků',
    poznamka: 'Maximální délka je 50 znaků',
    states: 'Vyberte stav klienta'
  }
});
