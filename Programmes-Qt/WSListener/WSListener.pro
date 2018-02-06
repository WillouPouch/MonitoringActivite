#-------------------------------------------------
#
# Project created by QtCreator 2017-02-13T15:29:36
#
#-------------------------------------------------

QT += core websockets network
QT -= gui widgets

CONFIG += c++11

#greaterThan(QT_MAJOR_VERSION, 4): QT += widgets

TARGET = WSListener
CONFIG += console
CONFIG -= app_bundle

TEMPLATE = app

# The following define makes your compiler emit warnings if you use
# any feature of Qt which as been marked as deprecated (the exact warnings
# depend on your compiler). Please consult the documentation of the
# deprecated API in order to know how to port your code away from it.
DEFINES += QT_DEPRECATED_WARNINGS

# You can also make your code fail to compile if you use deprecated APIs.
# In order to do so, uncomment the following line.
# You can also select to disable deprecated APIs only up to a certain version of Qt.
#DEFINES += QT_DISABLE_DEPRECATED_BEFORE=0x060000    # disables all the APIs deprecated before Qt 6.0.0


SOURCES += main.cpp\
    echoclient.cpp \
    detect.cpp \
    network.cpp \
    activite.cpp \
    listeactivite.cpp \
    anomaliesonore.cpp \
    listeanomaliesonore.cpp

HEADERS  += \
    echoclient.h \
    detect.h \
    network.h \
    activite.h \
    listeactivite.h \
    anomaliesonore.h \
    listeanomaliesonore.h
