<?php
/**
 * User: Denis
 * Date: 29.01.14
 * Time: 21:07
 */
class Tools extends Controller
{
    function renameTitle()
    {
        set_time_limit(1000);
        // get all attachments from the attachment table
        $this->load->model('attachment');
        $attachments = $this->attachment->get_attach();

        if (!empty($attachments)) {
            $count = array_fill_keys( [
                    'all',
                    'renamed'
                ],
                0
            );
            $filesDir = realpath(BASEPATH . '../files/');
            if ($filesDir && is_readable($filesDir)) {
                if ($handle = opendir($filesDir)) {
                    foreach ($attachments as $attachment) {
                        $oldFileName = $filesDir . '/' . $attachment->attach_name . $attachment->attach_ext;
                        // rename the file if it is exist
                        if (file_exists($oldFileName)) {
                            $newFileName = $this->microtime_float() . '_picture';
                            $newFilePath = $filesDir . '/' . $newFileName . $attachment->attach_ext;

                            $fileHand = fopen($oldFileName, 'r');
                            fclose($fileHand);

                            if (!rename($oldFileName, $newFilePath)) {
                                throw new Exception('file ' . $oldFileName . ' has not been renamed');
                            }
                            $newFileDir = $filesDir . '/renamed/' . $newFileName . $attachment->attach_ext;
                            copy($newFilePath, $newFileDir);
                            unlink($newFilePath);

                            $this->attachment->update_attachment(
                                $attachment->attach_id,
                                array(
                                    'attach_name' => $newFileName,
                                )
                            );
                            $count['renamed']++;
                            sleep(1);
                        }

                        // rename single_path file
                        if (!empty($attachment->attach_path)) {
                            $oldFileName = $filesDir . '/' . pathinfo($attachment->attach_path, PATHINFO_BASENAME);
                            // rename the file if it is exist
                            if (file_exists($oldFileName)) {
                                $newFileName = $this->microtime_float() . '_picture';
                                $this->attachment->update_attachment(
                                    $attachment->attach_id,
                                    array(
                                        'attach_path' => 'files/' . $newFileName . $attachment->attach_ext,
                                    )
                                );
                                $count['renamed']++;
                                sleep(1);
                            }
                        }

                        // rename single_path file
                        if (!empty($attachment->attach_single_path)) {
                            $oldFileName = $filesDir . '/' . pathinfo($attachment->attach_single_path, PATHINFO_BASENAME);
                            // rename the file if it is exist
                            if (file_exists($oldFileName)) {
                                $newFileName = $this->microtime_float() . '_picture_single';
                                $newFilePath = $filesDir . '/' . $newFileName . $attachment->attach_ext;

                                $fileHand = fopen($oldFileName, 'r');
                                fclose($fileHand);

                                if (!rename($oldFileName, $newFilePath)) {
                                    throw new Exception('file ' . $oldFileName . ' has not been renamed');
                                }
                                $newFileDir = $filesDir . '/renamed/' . $newFileName . $attachment->attach_ext;
                                copy($newFilePath, $newFileDir);
                                unlink($newFilePath);

                                $this->attachment->update_attachment(
                                    $attachment->attach_id,
                                    array(
                                        'attach_single_path' => 'files/' . $newFileName . $attachment->attach_ext,
                                    )
                                );
                                $count['renamed']++;
                                sleep(1);
                            }
                        }

                        // rename preview_path file
                        if (!empty($attachment->attach_preview_path)) {
                            $oldFileName = $filesDir . '/' . pathinfo($attachment->attach_preview_path, PATHINFO_BASENAME);
                            // rename the file if it is exist
                            if (file_exists($oldFileName)) {
                                $newFileName = $this->microtime_float() . '_picture_preview';
                                $newFilePath = $filesDir . '/' . $newFileName . $attachment->attach_ext;

                                $fileHand = fopen($oldFileName, 'r');
                                fclose($fileHand);

                                if (!rename($oldFileName, $newFilePath)) {
                                    throw new Exception('file ' . $oldFileName . ' has not been renamed');
                                }
                                $newFileDir = $filesDir . '/renamed/' . $newFileName . $attachment->attach_ext;
                                copy($newFilePath, $newFileDir);
                                unlink($newFilePath);

                                $this->attachment->update_attachment(
                                    $attachment->attach_id,
                                    array(
                                        'attach_preview_path' => 'files/' . $newFileName . $attachment->attach_ext,
                                    )
                                );
                                $count['renamed']++;
                                sleep(1);
                            }
                        }

                        // rename preview_main_path
                        if (!empty($attachment->attach_preview_main_path)) {
                            $oldFileName = $filesDir . '/' . pathinfo($attachment->attach_preview_main_path, PATHINFO_BASENAME);
                            // rename the file if it is exist
                            if (file_exists($oldFileName)) {
                                $newFileName = $this->microtime_float() . '_picture_preview_main';
                                $newFilePath = $filesDir . '/' . $newFileName . $attachment->attach_ext;

                                $fileHand = fopen($oldFileName, 'r');
                                fclose($fileHand);

                                if (!rename($oldFileName, $newFilePath)) {
                                    throw new Exception('file ' . $oldFileName . ' has not been renamed');
                                }
                                $newFileDir = $filesDir . '/renamed/' . $newFileName . $attachment->attach_ext;
                                copy($newFilePath, $newFileDir);
                                unlink($newFilePath);

                                $this->attachment->update_attachment(
                                    $attachment->attach_id,
                                    array(
                                        'attach_preview_main_path' => 'files/' . $newFileName . $attachment->attach_ext,
                                    )
                                );
                                $count['renamed']++;
                                sleep(1);
                            }
                        }
                        $count['all']++;
                    }
                }
            }
            echo "<pre>";
                var_dump( $count );
            echo "</pre>";exit;
        }
    }

    public function revertTitle()
    {
        $this->load->model('attachment');
        $attachments = $this->attachment->get_attach();

        if (!empty($attachments)) {
            foreach ($attachments as $attachment) {
                // main file
                $attachPathName = pathinfo(explode('/', $attachment->attach_path)[1], PATHINFO_FILENAME);
                $singlePathName = 'files/' . $attachPathName . '_single' . $attachment->attach_ext;
                $previewPathName = 'files/' . $attachPathName . '_preview' . $attachment->attach_ext;
                $previewMainPathName = 'files/' . $attachPathName . '_preview_main' . $attachment->attach_ext;

                $this->attachment->update_attachment(
                    $attachment->attach_id,
                    array(
                        'attach_name'               => $attachPathName,
                        'attach_single_path'        => file_exists($singlePathName) ? $singlePathName : null,
                        'attach_preview_path'       => file_exists($previewPathName) ? $previewPathName : null,
                        'attach_preview_main_path'  => file_exists($previewMainPathName) ? $previewMainPathName : null,
                    )
                );
            }
        }
        die('ok');
    }

    function microtime_float()
    {
        list($usec, $sec) = explode(" ", microtime());
        return intval((float)$usec + (float)$sec);
    }
}