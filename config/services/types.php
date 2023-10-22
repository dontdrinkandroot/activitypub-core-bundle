<?php

namespace Dontdrinkandroot\ActivityPubCoreBundle\Config\Services;

use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Core\Activity;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Core\Collection;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Core\CollectionPage;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Core\CoreObject;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Core\Link;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Core\OrderedCollection;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Core\OrderedCollectionPage;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Extended\Activity\Accept;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Extended\Activity\ActivityType;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Extended\Activity\Add;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Extended\Activity\Announce;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Extended\Activity\Arrive;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Extended\Activity\Block;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Extended\Activity\Create;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Extended\Activity\Delete;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Extended\Activity\Dislike;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Extended\Activity\Flag;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Extended\Activity\Follow;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Extended\Activity\Ignore;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Extended\Activity\Invite;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Extended\Activity\Join;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Extended\Activity\Leave;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Extended\Activity\Like;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Extended\Activity\Listen;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Extended\Activity\Move;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Extended\Activity\Offer;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Extended\Activity\Question;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Extended\Activity\Read;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Extended\Activity\Reject;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Extended\Activity\Remove;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Extended\Activity\TentativeAccept;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Extended\Activity\TentativeReject;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Extended\Activity\Travel;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Extended\Activity\Undo;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Extended\Activity\Update;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Extended\Activity\View;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Extended\Actor\ActorType;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Extended\Actor\Application;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Extended\Actor\Group;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Extended\Actor\Organization;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Extended\Actor\Person;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Extended\Actor\Service;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Extended\Link\Mention;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Extended\Object\Article;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Extended\Object\Audio;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Extended\Object\Document;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Extended\Object\Event;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Extended\Object\Image;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Extended\Object\Note;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Extended\Object\ObjectType;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Extended\Object\Page;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Extended\Object\Place;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Extended\Object\Profile;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Extended\Object\Relationship;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Extended\Object\Tombstone;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Extended\Object\Video;
use Dontdrinkandroot\ActivityPubCoreBundle\Serializer\TypeClassRegistry;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return function (ContainerConfigurator $configurator): void {
    $services = $configurator->services();

    $typeClassRegistryConfigurator = $services->get(TypeClassRegistry::class);

    /* Core Types */
    $typeClassRegistryConfigurator
        ->call('registerClass', [ObjectType::OBJECT->value, CoreObject::class])
        ->call('registerClass', [ObjectType::COLLECTION->value, Collection::class])
        ->call('registerClass', [ObjectType::ORDERED_COLLECTION->value, OrderedCollection::class])
        ->call('registerClass', [ObjectType::COLLECTION_PAGE->value, CollectionPage::class])
        ->call('registerClass', [ObjectType::ORDERED_COLLECTION_PAGE->value, OrderedCollectionPage::class]);

    /* Extended Object Types */
    $typeClassRegistryConfigurator
        ->call('registerClass', [ObjectType::ARTICLE->value, Article::class])
        ->call('registerClass', [ObjectType::AUDIO->value, Audio::class])
        ->call('registerClass', [ObjectType::DOCUMENT->value, Document::class])
        ->call('registerClass', [ObjectType::EVENT->value, Event::class])
        ->call('registerClass', [ObjectType::IMAGE->value, Image::class])
        ->call('registerClass', [ObjectType::NOTE->value, Note::class])
        ->call('registerClass', [ObjectType::PAGE->value, Page::class])
        ->call('registerClass', [ObjectType::PLACE->value, Place::class])
        ->call('registerClass', [ObjectType::PROFILE->value, Profile::class])
        ->call('registerClass', [ObjectType::RELATIONSHIP->value, Relationship::class])
        ->call('registerClass', [ObjectType::TOMBSTONE->value, Tombstone::class])
        ->call('registerClass', [ObjectType::VIDEO->value, Video::class]);

    /* Activity Types */
    $typeClassRegistryConfigurator
        ->call('registerClass', [ActivityType::ACTIVITY->value, Activity::class])
        ->call('registerClass', [ActivityType::ACCEPT->value, Accept::class])
        ->call('registerClass', [ActivityType::ADD->value, Add::class])
        ->call('registerClass', [ActivityType::ANNOUNCE->value, Announce::class])
        ->call('registerClass', [ActivityType::ARRIVE->value, Arrive::class])
        ->call('registerClass', [ActivityType::BLOCK->value, Block::class])
        ->call('registerClass', [ActivityType::CREATE->value, Create::class])
        ->call('registerClass', [ActivityType::DELETE->value, Delete::class])
        ->call('registerClass', [ActivityType::DISLIKE->value, Dislike::class])
        ->call('registerClass', [ActivityType::FLAG->value, Flag::class])
        ->call('registerClass', [ActivityType::FOLLOW->value, Follow::class])
        ->call('registerClass', [ActivityType::IGNORE->value, Ignore::class])
        ->call('registerClass', [ActivityType::INVITE->value, Invite::class])
        ->call('registerClass', [ActivityType::JOIN->value, Join::class])
        ->call('registerClass', [ActivityType::LEAVE->value, Leave::class])
        ->call('registerClass', [ActivityType::LIKE->value, Like::class])
        ->call('registerClass', [ActivityType::LISTEN->value, Listen::class])
        ->call('registerClass', [ActivityType::MOVE->value, Move::class])
        ->call('registerClass', [ActivityType::OFFER->value, Offer::class])
        ->call('registerClass', [ActivityType::QUESTION->value, Question::class])
        ->call('registerClass', [ActivityType::READ->value, Read::class])
        ->call('registerClass', [ActivityType::REJECT->value, Reject::class])
        ->call('registerClass', [ActivityType::REMOVE->value, Remove::class])
        ->call('registerClass', [ActivityType::TENTATIVE_ACCEPT->value, TentativeAccept::class])
        ->call('registerClass', [ActivityType::TENTATIVE_REJECT->value, TentativeReject::class])
        ->call('registerClass', [ActivityType::TRAVEL->value, Travel::class])
        ->call('registerClass', [ActivityType::UNDO->value, Undo::class])
        ->call('registerClass', [ActivityType::UPDATE->value, Update::class])
        ->call('registerClass', [ActivityType::VIEW->value, View::class]);

    /* Actor Types */
    $typeClassRegistryConfigurator
        ->call('registerClass', [ActorType::APPLICATION->value, Application::class])
        ->call('registerClass', [ActorType::GROUP->value, Group::class])
        ->call('registerClass', [ActorType::ORGANIZATION->value, Organization::class])
        ->call('registerClass', [ActorType::PERSON->value, Person::class])
        ->call('registerClass', [ActorType::SERVICE->value, Service::class]);

    /* Link Types */
    $typeClassRegistryConfigurator
        ->call('registerClass', [Link::TYPE, Link::class])
        ->call('registerClass', [Mention::TYPE, Mention::class]);
};
