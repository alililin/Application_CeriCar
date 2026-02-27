
$(function () {

    // fonction de notification
    function showNotification(message, cssClass) {
        var banner = $('#notification-banner');
        
        var finalClass = (cssClass === 'danger' || cssClass === 'error') ? 'alert-danger' : 'alert-success';
        
        banner.removeClass().addClass('alert ' + finalClass + ' alert-dismissible fade show')
              .html(message).slideDown(); 
        
        setTimeout(function () { 
            banner.slideUp(); 
        }, 4000);
    }

    
  
    function loadContent(url) {
        $('body').css('cursor', 'wait');
        
        $.ajax({
            url: url,
            type: 'GET',
            dataType: 'json', // On attend toujours du JSON
            success: function(response) {
                $('body').css('cursor', 'default');
                
                // Si le serveur renvoie du contenu HTML
                if (response.content) {
                    $('#content-area').html(response.content);
                    // Change l'URL dans le navigateur sans recharger
                    window.history.pushState(null, '', url);
                }
                
                // Si le serveur demande une redirection
                if (response.redirect) {
                    window.location.href = response.redirect;
                }
            },
            error: function() { 
                $('body').css('cursor', 'default');
                showNotification("Erreur de chargement de la page.", "danger"); 
            }
        });
    }

     //Intercepte tous les clics sur les liens qui ont la classe ajax-link
    $(document).on('click', '.ajax-link', function(e) {
        e.preventDefault(); 
        var url = $(this).attr('href');
        if (url && url !== '#') {

            loadContent(url);
        }
    });

   
    $(document).on('submit', '#login-form', function(e) {
        e.preventDefault();
        e.stopImmediatePropagation(); 
        
        var form = $(this);
        var btn = form.find('button[type="submit"]');
        var originalText = btn.text();
        
        btn.prop('disabled', true).text('Traitement...'); //

        $.ajax({
            url: form.attr('action'),
            type: 'POST',
            data: form.serialize(),
            dataType: 'json',
            success: function(response) {
                btn.prop('disabled', false).text(originalText);

                if (response.success) {
                    showNotification(response.message, 'success');

                    if (response.redirect) {
                        setTimeout(function() {
                            window.location.href = response.redirect;
                        }, 1000);
                    }
                    
                    else if (response.content) {
                        $('#').html(response.content);
                    } 

                } else {
                    // Gestion des erreurs
                    var msg = response.message || "Une erreur est survenue.";
                    showNotification(msg, 'danger');
                }
            },
            error: function() {
                showNotification("Erreur technique serveur.", "danger");
                btn.prop('disabled', false).text(originalText);
            }
        });
    });

    $(document).on('click', '.btn-logout', function(e) {
        e.preventDefault();
        var url = $(this).attr('href'); 

        // On change le curseur pour montrer que ça travaille
        $('body').css('cursor', 'wait');

        $.ajax({
            url: url,
            type: 'POST', // La déconnexion doit être un POST 
            dataType: 'json',
            success: function(response) {
                // Affiche la notification 
                if (response.message) {
                showNotification(response.message, 'success');
            }
                
                
               
                // On attend 1 seconde pour que l'utilisateur lise le message
                setTimeout(function() {
                    if (response.redirect) {
                        // Cela garantit que la page se recharge et que le menu redevient invité
                       window.location.replace(response.redirect);
                    }
                }, 1000);
            },
            error: function() {
                $('body').css('cursor', 'default');
                showNotification("Erreur lors de la déconnexion", "danger");
            }
        });
    });

    
    // RECHERCHE (GET Formulaire)
    
    $(document).on('submit', '#search-form', function (e) { 
        e.preventDefault();
        
        var form = $(this);
        var btn = form.find('button[type="submit"]');
        btn.prop('disabled', true);

        $.ajax({ 
            url: form.attr('action'), 
            type: 'GET', 
            data: form.serialize(), 
            dataType: 'json', 
            success: function (response) { 
                btn.prop('disabled', false);
                
                if(response.html) {
                     $('#results-wrapper').html(response.html); 
                } else if (response.content) {
                     $('#content-area').html(response.content); 
                }

                if(response.message) showNotification(response.message, 'success');
            },
            error: function () { 
                btn.prop('disabled', false);
                showNotification("Erreur lors de la recherche", "danger"); 
            }
        });
    });
    $(document).on('submit', '#form-proposer', function (e) { 
    e.preventDefault();
    
    var form = $(this);
    var btn = form.find('button[type="submit"]');
    btn.prop('disabled', true);

    $.ajax({ 
        url: form.attr('action'), 
        type: 'POST', 
        data: form.serialize(), 
        dataType: 'json', 
        success: function (response) { 
            btn.prop('disabled', false); 

            if (response.success) {
                showNotification(response.message, 'success');
                
                if (response.content) { 
                    $('#content-area').html(response.content);
                } 
               
                
            } 
            else {
                // CAS ERREUR 
                var msg = response.message || "Une erreur est survenue.";
                showNotification(msg, 'danger');
            }
        },
        error: function() {
            btn.prop('disabled', false);
            showNotification("Erreur technique serveur.", "danger");
        }
    });
    });

    //Formalaire de reservation 
     $(document).on('submit', '#form-signup', function (e) { 
    e.preventDefault();
    
    var form = $(this);
    var btn = form.find('button[type="submit"]');
    btn.prop('disabled', true);

    $.ajax({ 
        url: form.attr('action'), 
        type: 'POST', 
        data: form.serialize(), 
        dataType: 'json', 
        success: function (response) { 
            btn.prop('disabled', false); 

            if (response.success) {
                // CAS SUCCÈS : Vert
                showNotification(response.message, 'success');
                
                if (response.content) { 
                    $('#content-area').html(response.content);
                } 
                
            } 
            else {
                // CAS ERREUR 
                var msg = response.message || "Une erreur est survenue.";
                showNotification(msg, 'danger');
            }
        },
        error: function() {
            btn.prop('disabled', false);
            showNotification("Erreur technique serveur.", "danger");
        }
    });
    });
    
     $(document).on('submit', '#reservation-form', function (e) { 
    e.preventDefault();
    
    var form = $(this);
    var btn = form.find('button[type="submit"]');
    btn.prop('disabled', true);

    $.ajax({ 
        url: form.attr('action'), 
        type: 'POST', 
        data: form.serialize(), 
        dataType: 'json', 
        success: function (response) { 
            btn.prop('disabled', false); // On réactive le bouton

            if (response.success) {
                showNotification(response.message, 'success');
                
                if (response.content) { 
                    $('#content-area').html(response.content);
                } 
               
            } 
            else {
                // CAS ERREUR 
                var msg = response.message || "Une erreur est survenue.";
                showNotification(msg, 'danger');
            }
        },
        error: function() {
            btn.prop('disabled', false);
            showNotification("Erreur technique serveur.", "danger");
        }
    });
    });

});