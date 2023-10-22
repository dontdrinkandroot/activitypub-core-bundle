<?php

namespace Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Extended\Activity;

enum ActivityType: string
{
    case ACTIVITY = 'Activity';
    case ACCEPT = 'Accept';
    case TENTATIVE_ACCEPT = 'TentativeAccept';
    case ADD = 'Add';
    case ARRIVE = 'Arrive';
    case CREATE = 'Create';
    case DELETE = 'Delete';
    case FOLLOW = 'Follow';
    case IGNORE = 'Ignore';
    case JOIN = 'Join';
    case LEAVE = 'Leave';
    case LIKE = 'Like';
    case OFFER = 'Offer';
    case INVITE = 'Invite';
    case REJECT = 'Reject';
    case TENTATIVE_REJECT = 'TentativeReject';
    case REMOVE = 'Remove';
    case UNDO = 'Undo';
    case UPDATE = 'Update';
    case VIEW = 'View';
    case LISTEN = 'Listen';
    case READ = 'Read';
    case MOVE = 'Move';
    case TRAVEL = 'Travel';
    case ANNOUNCE = 'Announce';
    case BLOCK = 'Block';
    case FLAG = 'Flag';
    case DISLIKE = 'Dislike';
    case QUESTION = 'Question';
}
