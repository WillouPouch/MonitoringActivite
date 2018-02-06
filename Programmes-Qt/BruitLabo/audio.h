#ifndef AUDIO_H
#define AUDIO_H

#include <QtCore>
#include <QAudioDeviceInfo>
#include <QAudioInput>

class Audio : public QObject{
    Q_OBJECT

public:
    explicit Audio(QObject *parent = Q_NULLPTR, bool debug = false);

private:
    bool m_debug;
    QAudioDeviceInfo m_Inputdevice;
    QAudioFormat m_format;
    QAudioInput *m_audioInput;
    QIODevice *m_input;
    QByteArray m_buffer;

    void initializeAudio();
    void createAudioInput();

private slots:
    void readMore();

signals:
    void db_level(double);

};

#endif // AUDIO_H
