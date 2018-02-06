#include <QCoreApplication>

#include "detect.h"
#include "echoclient.h"


int main(int argc, char *argv[]) {

    if (argc != 4) {
        qDebug() << "Usage : " << argv[0] << " Address-IP-machine-BruitLabo  Address-IP-machine-EnregTV Address-IP-machine-DetectZone";

        return 1;
    }

    QCoreApplication a(argc, argv);

    QString addr_ip_BruitLabo = "ws://";
    addr_ip_BruitLabo += argv[1];
    addr_ip_BruitLabo += ":4420";

    QString addr_ip_EnregTV = "ws://";
    addr_ip_EnregTV += argv[2];
    addr_ip_EnregTV += ":4430";

    QString addr_ip_DetectZone = "ws://";
    addr_ip_DetectZone += argv[3];
    addr_ip_DetectZone += ":4440";

    EchoClient client_micro(QUrl(addr_ip_BruitLabo), Q_NULLPTR, true); //Ecoute des donnees micro
    EchoClient client_tv(QUrl(addr_ip_EnregTV), Q_NULLPTR, true); //Ecoute des donnees tv
    EchoClient client_zone(QUrl(addr_ip_DetectZone), Q_NULLPTR, true); //Ecoute des changements de zone
    Detect detect;

    QObject::connect(&client_micro, SIGNAL(signal_datareceived(QString)), &detect, SLOT(slot_datareceived(QString)));
    QObject::connect(&client_tv, SIGNAL(signal_datareceived(QString)), &detect, SLOT(slot_datareceived(QString)));
    QObject::connect(&client_zone, SIGNAL(signal_datareceived(QString)), &detect, SLOT(slot_datareceived(QString)));

    return a.exec();
}
