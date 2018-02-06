#include <QCoreApplication>

#include "zonetracker.h"
#include "echoserver.h"
#include "controller.h"


int main(int argc, char *argv[]) {

    QCoreApplication a(argc, argv);

    ZoneTracker zone_tracker;
    Controller controller;
    EchoServer server(4440);

    QObject::connect(&zone_tracker, SIGNAL(zone_changed(QString)), &controller, SLOT(zone_changed(QString)));
    QObject::connect(&controller, SIGNAL(send_data(QString)), &server, SLOT(slot_sendMessage(QString)));

    return a.exec();
}
