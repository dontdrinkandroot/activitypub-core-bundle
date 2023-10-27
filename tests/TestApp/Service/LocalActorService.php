<?php

namespace Dontdrinkandroot\ActivityPubCoreBundle\Tests\TestApp\Service;

use Dontdrinkandroot\ActivityPubCoreBundle\Model\LocalActorInterface;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\SignKey;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Extended\Actor\Actor;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Extended\Actor\Person;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Extended\Actor\Service;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\JsonLdContext;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Property\PublicKey;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Property\Uri;
use Dontdrinkandroot\ActivityPubCoreBundle\Service\Actor\LocalActorServiceInterface;
use Dontdrinkandroot\ActivityPubCoreBundle\Service\Actor\LocalActorUriGeneratorInterface;
use Dontdrinkandroot\ActivityPubCoreBundle\Tests\TestApp\Model\StaticLocalActor;
use RuntimeException;

class LocalActorService implements LocalActorServiceInterface
{
    private array $privateKeyPemByUserName = [
        'person' => <<<PEM
-----BEGIN RSA PRIVATE KEY-----
MIIEowIBAAKCAQEAzsMzNK4bE45JO678FC0OzhGuqvWCdbVkAEhzYOLO5RmYBFqW
QIPKDbIf2Fazt99TytufdA34SumjN1rlz4AqUXzG6eQYUJ3hh/xSRc8wuaRLZqu3
n3cBLtEw17anitng58HodvfsCUDf6TNFzlPj7o5G/CwXqD9eyC6uU6sAQHqd4A6P
MAka6W1QU3LV6UqWfJ3BmnQCu7QcTKeqXuaV0wCp1bIKMPH7WN44KX/H/5jkrliF
dAmGrV06CkADJgUjN4ZGTwhTrblOcZ6Ro88GwySglr59CZJWduvRP+RwS8V56xeg
uYQtiATu1++Z1MLepP/1xrI6KNokIqXUTLpFUQIDAQABAoIBAAXxA4ltwF7vPYj+
xgUhZ1XCGdIVVH6j6//7FP+xfM8GDYGAheVMNDPxDKu3kBoGS57ecUZROXOTo6pN
TSHJlc26J3AkqxMz+j1hYY7afZS0FSuZ3yCwt4K8JappOAbMLIOUZaT3iluYtuZ5
X/XmILxj78PC05o02fkoKD/Ev/DaV+/PVmWYoF/BLJ/FjpbQXF/NFyQFFXUO/i/A
Gr7e4RVL+GV5FOL/ad3lbC3i4F6VeVWuaB5+qeUZuSEK89cijz0I5IGde6H7wi+E
OEK1QCHTxgUDwsXqLXO+cngoSwRtd84OAY1Yj4n9ZevILHvq9BpWi3VcxF8EZ21n
elMm5q0CgYEA75c2gwjGOYQmmORKYIeq0FHDX5Q+sJHJEEsH+d2ifPIRNxuhnBG9
QnTbd6KN5T2+lPX82qAZQyiMPIoVrUaIA1kRFqRHsbnpRGPmPmSYd3yok+626T1h
py42+1/UJGxd6zcVEz7LZrYg4DVcd8viH/5UItzAzequYEKy2sdfb90CgYEA3Oxn
mddCnNUcQNaLz+XT8pigJH+iyTCNQJSHuz9JVkNydqSFfU1H+blA91XDQlvsMq1H
0FwBVq8KzKnrngI729rFCuU9dJathdG3rQIKb7pQb1/GoQ+5AwfhBPZsXmXYsSAs
EPubZXMM86lTGvQfTlzoALyT+DetN4w51XJLDgUCgYA5Ya3clC1leREFbSejFtsC
KZLxQUACaegNzuqKHVrdMdyNpkB+cIEzeWlWrcfuL2uFoaR9d/qU6xErLqciaNIK
ezpsgcvp9Oy5RHPQXadmdqSpSXLlSZ4pvBfO/JSCZLHZs8eIZHGyl8wn5p/O0TXH
E9JyxwwmRR6eT1smqrlgwQKBgQC+rdiZgp6+6H1TRRo1XUO7DpqiBfwFtD8mb0xb
hDsTFnHUDxocVTh7RLbbA43dV6Oc9cyW/OI25CvpC/wOTBVIJCGPzt5lI6wvZRwo
WiuR1XiZOEwjNYPVJtbDxsEwFK2b6429Nr0gKdYS9KGDERN4Ol4QTLNWOQ/rcr90
CArZ1QKBgF7I2lfU2tPLZpbY3Nv7Ww6QcBQvZZ08q0FwF9emOJtJNyUH85XoXrzX
81aJpZyApTh2eDZ5JYykz7Gi1hEpPmS5uoIZGKyRrGiwqm6IfwUN2N1JUb3WwBic
krjA/89F2dBnRAfRVN7/Y67WxpQIgG9GxZDsm2chMpznXk8PYBxM
-----END RSA PRIVATE KEY-----
PEM,
        'service' => <<<PEM
-----BEGIN RSA PRIVATE KEY-----
MIIEowIBAAKCAQEAi29XYOkLqoYfXA4ZxjQmz7KaUXHRXwToGkWiYmTT5j1IYEW7
UZKGrP5hbccWcmCeGlafRnM03oaR0csvlcSGyRTMUHVQSt5zBMVb+dZKw688KEGh
n1gx3bt/jOcqLWr80D2HeFXlrgK+UzCcZMOO83J25/pCcfkVoqPnS38LeyMTBoij
B7SPMoQwU3s942sCMsOy4li4mxZ3Yl3IGfJFfqq7UviKP4R/lpyI1mFNcLJbrcrZ
xnXiu4yNW0GGPTtS8KotxKqn4oUFie5X42AnXvl1HEYz3MxDR2l5dC7A3fQWa1Wm
Qro/7M4jREA7XhbzsT7DhNlkj6LblXovbVlC3QIDAQABAoIBAAd/+o+3lhonsua4
Y9hRtGeGLR9fXP2Pt02E3FcPIrsaJcGQtQj0tcv26aLf5NjlhVHxjqMMwSKNBql/
R9tC7ssNetXpd+NApttYieBDQtJwVqIfSzdVpTNoVlQfMcx8M9M4BzuvE+nk5nuo
1c8Xs7s4t3IDEVetdOWPWMdEGW5+LQ19qFUwVp0nId2/n6InpJIO3PynaLx2bsy4
rYfoTg64ChpoPvNmPyWTBdXR2WlY1zc7RpkKsxXrm2tOF09G4hbKYlYW4kArf/5G
z+rvrsxFlz89Q5RusvTPUDm9+D2XO17x2z2iwmqRZ/zQgceN4TGWYCk1TmM4ExDV
StjQSqMCgYEAvBYM7APcUxwn0t/OZ5NCVJ8OgxL6uMSCWPBCSZaa+EG+tX5E/i6H
3A/FRe6Pi0A90iT+6u9w4F+5PHNRepoOgRjXdJF+CFm7qZmMe0AiJakwl4ioJHjF
HwtKLp3WWLuOKLj+5t5KilnEVXUZkqukvwkqh2EcDUJuPeXukaIVeGcCgYEAvcgp
qi/Ewd11RAkYg+N/CdBujvy8BhuHOVdMBhIIkx7VueiLn2FqfaVB1z2B24UmiV6N
kCHZekGaJwCQJZ+4AnVKntjnVOHYuKv6qpVBUrG50vqYPeBZ409iQSrR1QT8/DNx
wb7wHaWkdo1ih26EtqyZLjvQ8wQVy9f/t/0D8BsCgYAmZBXVIuCY8jlKuLYHvC4g
2ap7pKcaibnVb40IOj59h+XmY9SvUU4X4/wvTwdrs/wqZbTGvYL7uW404ZDzBnkJ
bsmjmILyL2a3sojTK38M0uEBPTqc3y3VLVfB9iOnTvkwZLpa42qxnKsPimxi3Lgu
6i8NHQw9xJ598e3lOgFJ5wKBgQCxALL7a7oTJj1syx72Q4QE30V+TvH+sEYakPTy
5Hbi4GtuDRnL+MudjDgwS8mFuFYM4QcfWrK/d9gScFABB0pT4JlMNfjsDghXlO8h
kjtuqRwrTlYXv9uWSj/Vj95M024wurpqfW7t98PAXnV64vUcezYTDO8A+NprWHXE
YFL/6QKBgHsKMabyg6jpPW/WvcQY5xb95yg5kTHgxrEzd95uNs3SEXOgLicONWIX
kagRBP3ll1BMjR+kWmT6vj66PFLU8J2F4AHnThbgBcGngHdXvm2k4r60ncoTQOSB
E26Mchas57ME3J/HF8f1fXkKSee7uwI+lH7xdx2n00FZMAVT1ucU
-----END RSA PRIVATE KEY-----
PEM
    ];

    private array $publicKeyPemByUserName = [
        'person' => <<<PEM
-----BEGIN PUBLIC KEY-----
MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAzsMzNK4bE45JO678FC0O
zhGuqvWCdbVkAEhzYOLO5RmYBFqWQIPKDbIf2Fazt99TytufdA34SumjN1rlz4Aq
UXzG6eQYUJ3hh/xSRc8wuaRLZqu3n3cBLtEw17anitng58HodvfsCUDf6TNFzlPj
7o5G/CwXqD9eyC6uU6sAQHqd4A6PMAka6W1QU3LV6UqWfJ3BmnQCu7QcTKeqXuaV
0wCp1bIKMPH7WN44KX/H/5jkrliFdAmGrV06CkADJgUjN4ZGTwhTrblOcZ6Ro88G
wySglr59CZJWduvRP+RwS8V56xeguYQtiATu1++Z1MLepP/1xrI6KNokIqXUTLpF
UQIDAQAB
-----END PUBLIC KEY-----
PEM,
        'service' => <<<PEM
-----BEGIN PUBLIC KEY-----
MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAi29XYOkLqoYfXA4ZxjQm
z7KaUXHRXwToGkWiYmTT5j1IYEW7UZKGrP5hbccWcmCeGlafRnM03oaR0csvlcSG
yRTMUHVQSt5zBMVb+dZKw688KEGhn1gx3bt/jOcqLWr80D2HeFXlrgK+UzCcZMOO
83J25/pCcfkVoqPnS38LeyMTBoijB7SPMoQwU3s942sCMsOy4li4mxZ3Yl3IGfJF
fqq7UviKP4R/lpyI1mFNcLJbrcrZxnXiu4yNW0GGPTtS8KotxKqn4oUFie5X42An
Xvl1HEYz3MxDR2l5dC7A3fQWa1WmQro/7M4jREA7XhbzsT7DhNlkj6LblXovbVlC
3QIDAQAB
-----END PUBLIC KEY-----
PEM
    ];

    public function __construct(
        private readonly LocalActorUriGeneratorInterface $localActorUriGenerator,
    ) {
    }

    /**
     * {@inheritdoc}
     */
    public function findLocalActorByUsername(string $username): ?LocalActorInterface
    {
        return match ($username) {
            'person' => new StaticLocalActor($username),
            'service' => new StaticLocalActor($username),
            default => null
        };
    }

    /**
     * {@inheritdoc}
     */
    public function findLocalActorByUri(Uri $uri): ?LocalActorInterface
    {
        $username = $this->localActorUriGenerator->matchUsername($uri);
        if (null === $username) {
            return null;
        }

        return $this->findLocalActorByUsername($username);
    }

    /**
     * {@inheritdoc}
     */
    public function getSignKey(LocalActorInterface $localActor): SignKey
    {
        $username = $localActor->getUsername();
        if (!array_key_exists($username, $this->privateKeyPemByUserName)) {
            throw new RuntimeException('Local Actor not found');
        }

        $id = $this->localActorUriGenerator->generateId($username);
        return new SignKey(
            id: $id->withFragment('main-key'),
            owner: $id,
            privateKeyPem: $this->privateKeyPemByUserName[$username],
            publicKeyPem: $this->publicKeyPemByUserName[$username]
        );
    }

    /**
     * {@inheritdoc}
     */
    public function toActivityPubActor(LocalActorInterface $localActor): Actor
    {
        $username = $localActor->getUsername();
        $actor = match ($username) {
            'person' => new Person(),
            'service' => new Service(),
            default => throw new RuntimeException('Unknown username: ' . $username)
        };
        $actor->jsonLdContext = new JsonLdContext(['https://w3id.org/security/v1']);
        $actor->id = $this->localActorUriGenerator->generateId($username);
        $actor->inbox = $this->localActorUriGenerator->generateInbox($username);
        $actor->outbox = $this->localActorUriGenerator->generateOutbox($username);
        $actor->preferredUsername = $username;
        $actor->publicKey = new PublicKey(
            id: $actor->getId()->withFragment('main-key'),
            owner: $actor->getId(),
            publicKeyPem: $this->publicKeyPemByUserName[$username]
        );

        return $actor;
    }

    /**
     * {@inheritdoc}
     */
    public function provide(Uri $uri, ?SignKey $signKey): Actor|false|null
    {
        $username = $this->localActorUriGenerator->matchUsername($uri);
        if (null === $username) {
            return false;
        }

        $localActor = $this->findLocalActorByUsername($username);
        if (null === $localActor) {
            return null;
        }

        return $this->toActivityPubActor($localActor);
    }
}
