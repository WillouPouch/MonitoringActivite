#include <QCoreApplication>

#include "audio.h"
#include "echoserver.h"
#include "controller.h"

int main(int argc, char *argv[]){

    QCoreApplication a(argc, argv);

    Audio audio;

    EchoServer server(4420, true);
    Controller controller;
    QObject::connect(&audio, SIGNAL(db_level(double)), &controller, SLOT(db_level(double)));
    QObject::connect(&controller, SIGNAL(send_data(QString)), &server, SLOT(slot_sendMessage(QString)));

    return a.exec();
}
