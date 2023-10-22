<?php

namespace Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Extended\Object;

enum ObjectType: string
{
    case OBJECT = 'Object';
    case COLLECTION = 'Collection';
    case NOTE = 'Note';
    case PLACE = 'Place';
    case ARTICLE = 'Article';
    case EVENT = 'Event';
    case DOCUMENT = 'Document';
    case IMAGE = 'Image';
    case ORDERED_COLLECTION = 'OrderedCollection';
    case COLLECTION_PAGE = 'CollectionPage';
    case TOMBSTONE = 'Tombstone';
    case ORDERED_COLLECTION_PAGE = 'OrderedCollectionPage';
    case RELATIONSHIP = 'Relationship';
    case AUDIO = 'Audio';
    case VIDEO = 'Video';
    case PAGE = 'Page';
    case PROFILE = 'Profile';
}
