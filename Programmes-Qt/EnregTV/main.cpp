#include <QCoreApplication>

#include "tvtracker.h"
#include "echoserver.h"
#include "controller.h"


int main(int argc, char *argv[]) {

    if (argc != 2) {
        qDebug() << "Usage : " << argv[0] << " tps_ms_tv_on_off";
        return 1;
    }

    QString tps_ms_tv_on_off = argv[1];

    QCoreApplication a(argc, argv);

    TvTracker tv_tracker;
    Controller controller(tps_ms_tv_on_off.toInt(), Q_NULLPTR, true);
    EchoServer server(4430);

    QObject::connect(&tv_tracker, SIGNAL(tv_on()), &controller, SLOT(tv_on()));
    QObject::connect(&tv_tracker, SIGNAL(tv_off()), &controller, SLOT(tv_off()));
    QObject::connect(&controller, SIGNAL(send_data(QString)), &server, SLOT(slot_sendMessage(QString)));

    return a.exec();
}
