import React from "react";
import { VStack, Text, HStack } from "@chakra-ui/react";
import { useData } from "@utils/Context";
import { dateFormat } from "@utils/helper";

const ProposeTextChat = ({ data, isAccepted }) => {
    const { selectedData } = useData();

    return (
        <VStack width="100%" padding={3} alignItems="flex-start">
            {isAccepted ? (
                <Text>
                    Proyek {selectedData.project.count} buah{" "}
                    <Text
                        as="a"
                        href={`/home/project/${selectedData.project_id}`}
                    >
                        <strong>{selectedData.project.name}</strong> #
                        {selectedData.project_id}
                    </Text>{" "}
                    telah disetujui.
                </Text>
            ) : (
                <Text>
                    Kamu telah mengajukan Proyek {selectedData.project.count}{" "}
                    buah{" "}
                    <Text
                        as="a"
                        href={`/home/project/${selectedData.project_id}`}
                    >
                        <strong>{selectedData.project.name}</strong> #
                        {selectedData.project_id}
                    </Text>{" "}
                </Text>
            )}
            {data.negotiation ? (
                <>
                    <HStack width="100%" justifyContent="space-between">
                        <Text>Harga per Unit</Text>
                        <Text color="gray.400">{data.negotiation.cost}</Text>
                    </HStack>
                    <HStack width="100%" justifyContent="space-between">
                        <Text>Mulai Pengerjaan</Text>
                        <Text color="gray.400" textAlign="right">
                            {dateFormat(data.negotiation.start_date)}
                        </Text>
                    </HStack>
                    <HStack width="100%" justifyContent="space-between">
                        <Text>Selesai Pengerjaan</Text>
                        <Text color="gray.400" textAlign="right">
                            {dateFormat(data.negotiation.deadline)}
                        </Text>
                    </HStack>
                </>
            ) : null}
        </VStack>
    );
};

export default ProposeTextChat;
