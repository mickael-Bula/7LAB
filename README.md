# Utilisation du design pattern Observer avec l'event subscriber de Symfony

SOURCE : Symfony 5, Etienne LANGLET, p. 275 et suiv.

Pour éviter d'avoir des 'fat controller', j'utilise l'event subscriber de Symfony.
Pour ce faire, il faut injecter l'interface dans le controller (l'appel directement depuis le container ne fonctionne pas).
Ensuite, on transmet l'événement avec ses arguments :

```php
$this->eventDispatcher->dispatch(new GenericEvent($brands, $fuels), 'filters');
```

Je déclare une classe pour gérer les actions à réaliser lorsque l'évènement surveillé est déclenché.
Cette classe implémente l'interface *EventSubscriberInterface*.
La classe se trouve dans App\Service\DispatcherService;

Dans le fichier config/services.yaml, je configure la classe gérant cet évènement.
Mon service *DispatcherService* est **branché** sur le *kernel.event_listener*. Quand l'évènement **filters** est déclenchée, il appelle la méthode **onFilters**.

```yaml
    dispatcher:
        class: App\Service\DispatcherService
        tags:
            - { name: kernel.event_listener, event: filters, method: onFilters }
```